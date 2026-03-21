<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Services\ExamAutoEvaluationService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Setting;
use App\Jobs\SendExamViolationNotifications;
use App\Support\ExamPayloadCache;

class ExamController extends Controller
{
    public function index()
    {
        $student = Auth::user();
        $now = now();

        $exams = Exam::where('school_id', $student->school_id)
            ->where('class', $student->grade)
            ->where('status', 'published')
            ->where('exam_type', '!=', 'mock')
            ->where(function ($query) use ($now) {
                $query->whereHas('schedule', function ($q) use ($now) {
                    $q->where('end_at', '>', $now);
                })->orWhereDoesntHave('schedule');
            })
            ->with('schedule')
            ->latest()
            ->paginate(10);

        $attemptedExamIds = ExamAttempt::where('user_id', $student->id)
            ->pluck('exam_id')
            ->toArray();

        return view('student.exams.index', compact('exams', 'attemptedExamIds'));
    }

    public function mock()
    {
        $student = Auth::user();

        $exams = Exam::where('school_id', $student->school_id)
            ->where('exam_type', 'mock')
            ->where('status', 'published')
            ->latest()
            ->paginate(10);

        return view('student.exams.mock', compact('exams'));
    }

    public function history()
    {
        $student = Auth::user();
        $now = now();

        $exams = Exam::where('school_id', $student->school_id)
            ->where('class', $student->grade)
            ->where('status', 'published')
            ->whereHas('schedule', function ($q) use ($now) {
                $q->where('end_at', '<=', $now);
            })
            ->with('schedule')
            ->latest()
            ->paginate(10);

        return view('student.exams.history', compact('exams'));
    }

    public function live(Request $request, $id)
    {
        $student = Auth::user();
        $now = now();

        $exam = Exam::where('school_id', $student->school_id)
            ->where('id', $id)
            ->where('status', 'published')
            ->with(['schedule'])
            ->firstOrFail();

        $school = School::find($student->school_id);
        [$instructionItems, $instructionSource] = $this->resolveLiveExamInstructions($exam, $school);

        if ($exam->exam_type !== 'mock' && !$request->boolean('begin')) {
            return view('student.exams.live', [
                'exam' => $exam,
                'instructionItems' => $instructionItems,
                'instructionSource' => $instructionSource,
                'preExamMode' => true,
                'questionsData' => [],
                'remainingSeconds' => 0,
                'sessionToken' => '',
            ]);
        }

        // 1. Strict Schedule Validation (Skip for Mock)
        if ($exam->exam_type !== 'mock') {
            if (!$exam->schedule || !$now->between($exam->schedule->start_at, $exam->schedule->end_at)) {
                return redirect()->route('student.exams.index')->with('error', 'This exam is not currently live.');
            }
        }

        // 2. Lifecycle Management: Create or Retrieve Attempt (Safe method)
        $attempt = ExamAttempt::firstOrNew([
            'user_id' => $student->id,
            'exam_id' => $exam->id,
        ]);

        // Mock Exam: Allow retakes if expired or submitted
        if ($exam->exam_type === 'mock' && $attempt->exists) {
            $baseExpiresAt = $attempt->expires_at ?? $attempt->started_at->copy()->addMinutes($exam->duration_minutes);
            $expiresAt = $baseExpiresAt->addSeconds($attempt->extra_time_seconds ?? 0);

            if ($attempt->submitted_at || $attempt->status !== 'in_progress' || $now->greaterThan($expiresAt)) {
                $attempt->delete();
                $attempt = new ExamAttempt([
                    'user_id' => $student->id,
                    'exam_id' => $exam->id,
                ]);
            }
        }

        if (!$attempt->exists) {
            $attempt->school_id = $student->school_id;
            $attempt->started_at = $now;
            $attempt->status = 'in_progress';
            $attempt->expires_at = $now->copy()->addMinutes($exam->duration_minutes);
            $attempt->ip_address = request()->ip();
            $attempt->save();
        }

        // 3. Check Status
        if ($exam->exam_type !== 'mock' && $attempt->submitted_at) {
            return redirect()->route('student.exams.index')->with('error', 'You have already submitted this exam.');
        }

        // 4. Calculate Duration & Expiry
        $baseExpiresAt = $attempt->expires_at ?? $attempt->started_at->copy()->addMinutes($exam->duration_minutes);
        $expiresAt = $baseExpiresAt->addSeconds($attempt->extra_time_seconds ?? 0);

        // Auto-expire if time passed
        if ($now->greaterThan($expiresAt)) {
            $attempt->submitted_at = $now;
            $attempt->status = 'expired';
            $attempt->save();
            return redirect()->route('student.exams.index')->with('error', 'Exam time has expired.');
        }

        // 5. Session Management: Generate new token for this specific window/tab
        $sessionToken = Str::random(64);
        $attempt->session_token = $sessionToken;
        $attempt->last_activity_at = $now;
        $attempt->save();

        $remainingSeconds = (int) $now->diffInSeconds($expiresAt, false);

        // Fetch questions using selected_questions JSON column
        $questionIds = $exam->selected_questions;
        if (is_string($questionIds)) {
            $questionIds = json_decode($questionIds, true) ?? [];
        }

        // Fix Issue 4: Question Order Integrity
        // Check if order is already saved in attempt to persist it across reloads
        $savedOrder = $attempt->question_order;

        if (empty($savedOrder)) {
            if ($exam->shuffle_questions) {
                $questionIds = collect($questionIds)->shuffle()->values()->toArray();
            }
            $attempt->question_order = json_encode($questionIds);
            $attempt->save();
            $finalQuestionIds = $questionIds;
        } else {
            $finalQuestionIds = is_string($savedOrder) ? json_decode($savedOrder, true) : $savedOrder;
        }

        $cachedQuestions = collect(ExamPayloadCache::getOrWarm($exam))->keyBy('id');

        $questionsData = collect(is_array($finalQuestionIds) ? $finalQuestionIds : [])
            ->map(fn ($questionId) => $cachedQuestions->get((int) $questionId))
            ->filter()
            ->map(function ($question) use ($exam) {
                if ($exam->exam_type !== 'mock') {
                    unset($question['correct_option']);
                }

                return $question;
            })
            ->values();

        if ($exam->exam_type === 'mock') {
            return view('student.exams.live_mock', compact('exam', 'questionsData', 'remainingSeconds', 'sessionToken'));
        }

        return view('student.exams.live', [
            'exam' => $exam,
            'attempt' => $attempt,
            'questionsData' => $questionsData,
            'remainingSeconds' => $remainingSeconds,
            'sessionToken' => $sessionToken,
            'instructionItems' => $instructionItems,
            'instructionSource' => $instructionSource,
            'preExamMode' => false,
        ]);
    }

    private function resolveLiveExamInstructions(Exam $exam, ?School $school): array
    {
        $examInstructions = $this->normalizeInstructionItems($exam->instructions);

        if (!empty($examInstructions)) {
            return [$examInstructions, 'exam'];
        }

        $schoolRules = $this->decodeJsonPayload($school?->exam_rules);
        $schoolInstructions = $this->normalizeInstructionItems(
            is_array($schoolRules) ? data_get($schoolRules, 'default_instructions') : $schoolRules
        );

        if (!empty($schoolInstructions)) {
            return [$schoolInstructions, 'school'];
        }

        $globalRules = $this->decodeJsonPayload(
            Setting::query()->where('key', 'default_exam_rules')->value('value')
        );
        $globalInstructions = $this->normalizeInstructionItems(
            is_array($globalRules) ? data_get($globalRules, 'default_instructions') : $globalRules
        );

        if (!empty($globalInstructions)) {
            return [$globalInstructions, 'global'];
        }

        return [[], 'none'];
    }

    private function decodeJsonPayload($value)
    {
        while (is_string($value)) {
            $decoded = json_decode($value, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                break;
            }

            $value = $decoded;
        }

        return $value;
    }

    private function normalizeInstructionItems($instructions): array
    {
        if (empty($instructions)) {
            return [];
        }

        $instructions = $this->decodeJsonPayload($instructions);

        if (is_string($instructions)) {
            $instructions = preg_split("/\r\n|\n|\r/", $instructions) ?: [];
        }

        if (!is_array($instructions)) {
            return [];
        }

        return collect($instructions)
            ->map(function ($item) {
                if (is_array($item)) {
                    $item = implode(' ', array_filter($item, fn ($value) => is_scalar($value) && trim((string) $value) !== ''));
                }

                return trim((string) $item);
            })
            ->filter()
            ->values()
            ->all();
    }
    public function submit(Request $request, $id)
    {
        $student = Auth::user();
        $exam = Exam::findOrFail($id);

        $attempt = ExamAttempt::where('user_id', $student->id)
            ->where('exam_id', $exam->id)
            ->firstOrFail();

        if ($attempt->submitted_at) {
            return redirect()->route('student.exams.index')
                ->with('error', 'Exam already submitted.');
        }

        if ($request->input('session_token') !== $attempt->session_token) {
            return redirect()->route('student.exams.index')
                ->with('error', 'Session expired.');
        }

        $now = now();
        $allowedEndTime = $attempt->started_at
            ->copy()
            ->addMinutes($exam->duration_minutes)
            ->addSeconds($attempt->extra_time_seconds ?? 0)
            ->addSeconds(120);

        if ($now->greaterThan($allowedEndTime)) {
            $attempt->update([
                'submitted_at' => $now,
                'status' => 'expired',
            ]);

            return redirect()->route('student.exams.index')
                ->with('error', 'Time exceeded.');
        }

        $answers = $request->input('answers', []);

        if (is_string($answers)) {
            $answers = json_decode($answers, true) ?? [];
        }

        if (!is_array($answers)) {
            $answers = [];
        }

        app(ExamAutoEvaluationService::class)
            ->evaluate($attempt, $answers);

        return redirect()->route('student.exams.history')
            ->with('success', 'Exam submitted successfully.');
    }
    public function logViolation(Request $request, $id)
    {
        $student = Auth::user();
        // Verify exam exists
        $exam = Exam::findOrFail($id);

        // Find active attempt
        $attempt = ExamAttempt::where('user_id', $student->id)
            ->where('exam_id', $exam->id)
            ->first();

        if (!$attempt || $attempt->submitted_at) {
            return response()->json(['status' => 'error', 'message' => 'Invalid attempt'], 400);
        }

        // Validate Session Token
        if ($request->input('session_token') !== $attempt->session_token) {
            return response()->json(['status' => 'error', 'message' => 'Session conflict: Exam opened in another tab.'], 409);
        }

        // Log the violation
        DB::table('exam_violations')->insert([
            'attempt_id' => $attempt->id,
            'user_id' => $student->id,
            'type' => $request->input('type', 'unknown'),
            'occurred_at' => now(),
            'ip_address' => $request->ip(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        SendExamViolationNotifications::dispatch(
            $student->id,
            $exam->id,
            $attempt->id,
            (string) $request->input('type', 'unknown')
        );

        // Check total violations
        $count = DB::table('exam_violations')->where('attempt_id', $attempt->id)->count();
        $maxViolations = 3; // Sync this with your frontend/exam settings

        if ($count > $maxViolations) {
            // Fix Issue 3: Server Side Enforcement
            $attempt->status = 'terminated';
            $attempt->submitted_at = now();
            $attempt->terminated_reason = 'Violation limit exceeded (' . $count . ' violations)';
            $attempt->save();

            return response()->json([
                'status' => 'terminate',
                'message' => 'Violation limit exceeded. Exam will be submitted.',
                'count' => $count
            ]);
        }

        return response()->json([
            'status' => 'recorded',
            'count' => $count
        ]);
    }

    public function heartbeat(Request $request, $id)
    {
        $student = Auth::user();
        $attempt = ExamAttempt::where('user_id', $student->id)
            ->where('exam_id', $id)
            ->first();

        if (!$attempt) {
            return response()->json(['status' => 'error'], 404);
        }

        // Validate Session
        if ($request->input('session_token') !== $attempt->session_token) {
            return response()->json(['status' => 'terminated', 'reason' => 'Session conflict'], 409);
        }

        // Update Activity
        $attempt->update(['last_activity_at' => now()]);

        // Check Status
        if (in_array($attempt->status, ['submitted', 'expired', 'terminated'])) {
            return response()->json([
                'status' => $attempt->status,
                'reason' => $attempt->terminated_reason
            ]);
        }

        // Calculate remaining time including extra time
        $expiresAt = $attempt->expires_at->copy()->addSeconds($attempt->extra_time_seconds);
        $remaining = now()->diffInSeconds($expiresAt, false);

        return response()->json([
            'status' => 'ok',
            'remaining_seconds' => max(0, $remaining)
        ]);
    }

    public function signal(Request $request, $attemptId)
    {
        $student = Auth::user();
        $attempt = ExamAttempt::where('user_id', $student->id)->where('id', $attemptId)->firstOrFail();
        $stream = \App\Models\ExamStream::findOrFail($request->stream_id);

        // Security check
        if ($stream->attempt_id !== $attempt->id) {
            return response()->json(['status' => 'error', 'message' => 'Mismatched attempt'], 403);
        }

        if ($request->type === 'offer' && $request->payload) {
            $stream->update([
                'offer' => $request->payload,
                'status' => 'offer_sent'
            ]);
            return response()->json(['status' => 'offer_sent']);
        }

        if ($request->type === 'student_ice' && $request->payload) {
            $stream->student_ice_candidates .= $request->payload . '||';
            $stream->save();
            return response()->json(['status' => 'ice_saved']);
        }

        return response()->json(['status' => 'invalid_request'], 400);
    }

    public function pollSignals(Request $request, $attemptId)
    {
        $student = Auth::user();
        $attempt = ExamAttempt::where('user_id', $student->id)->where('id', $attemptId)->firstOrFail();

        $signals = [];

        // 1. Check for new viewer requests
        $newRequests = \App\Models\ExamStream::where('attempt_id', $attempt->id)->where('status', 'requesting')->get();
        foreach ($newRequests as $req) {
            $signals[] = ['type' => 'new_viewer', 'stream_id' => $req->id, 'session_id' => $req->viewer_session_id];
        }

        // 2. Check for answers and ICE candidates from viewers
        $activeStreams = \App\Models\ExamStream::where('attempt_id', $attempt->id)->whereIn('status', ['offer_sent', 'connected'])->get();

        foreach ($activeStreams as $stream) {
            if ($stream->answer) {
                $signals[] = ['type' => 'answer', 'stream_id' => $stream->id, 'payload' => $stream->answer];
                $stream->update(['answer' => null, 'status' => 'connected']); // Consume answer
            }
            if ($stream->viewer_ice_candidates) {
                $signals[] = ['type' => 'viewer_ice', 'stream_id' => $stream->id, 'payload' => $stream->viewer_ice_candidates];
                $stream->update(['viewer_ice_candidates' => null]); // Consume ICE
            }
        }

        return response()->json($signals);
    }

    public function result($attemptId)
    {
        $student = auth()->user();

        $firstAttempt = ExamAttempt::with('exam')
            ->where('id', $attemptId)
            ->where('user_id', $student->id)
            ->firstOrFail();

        // 🚨 If not approved → stop here
        if ($firstAttempt->approval_status !== 'approved') {
            return view('student.exams.result-pending', [
                'status' => $firstAttempt->approval_status
            ]);
        }

        $exam = $firstAttempt->exam;

        $allAttempts = ExamAttempt::with('exam')
            ->where('user_id', $student->id)
            ->whereHas('exam', function ($q) use ($exam) {
                $q->where('title', $exam->title)
                    ->where('academic_session', $exam->academic_session)
                    ->where('class', $exam->class);
            })
            ->where('approval_status', 'approved')
            ->get();

        if ($allAttempts->isEmpty()) {
            $allAttempts = collect([$firstAttempt]);
        }

        // Fetch school dynamically
        $school = School::find($student->school_id);

        return view('student.exams.result', compact(
            'student',
            'school',
            'allAttempts'
        ));
    }


    public function results()
    {
        $attempts = ExamAttempt::with('exam')
            ->where('user_id', auth()->id())
            ->whereHas('exam', function ($q) {
                $q->where('exam_type', '!=', 'mock');
            })
            ->latest()
            ->get();

        // Group by Title + Session
        $grouped = $attempts->groupBy(function ($item) {
            return $item->exam->title . '|' . $item->exam->academic_session;
        });

        return view('student.exams.results', compact('grouped'));
    }

    public function downloadMarksheet($attemptId)
    {
        $student = auth()->user();

        $firstAttempt = ExamAttempt::with('exam')
            ->where('id', $attemptId)
            ->where('user_id', $student->id)
            ->firstOrFail();

        $exam = $firstAttempt->exam;

        // Fetch all subjects of same exam
        $allAttempts = ExamAttempt::with('exam')
            ->where('user_id', $student->id)
            ->whereHas('exam', function ($q) use ($exam) {
                $q->where('title', $exam->title)
                    ->where('academic_session', $exam->academic_session)
                    ->where('class', $exam->class);
            })
            ->where('approval_status', 'approved')
            ->get();

        $school = School::find($student->school_id);

        $pdf = Pdf::loadView('student.exams.marksheet_pdf', compact(
            'student',
            'allAttempts',
            'school'
        ))->setPaper('a4');

        return $pdf->download('Marksheet.pdf');
    }


}

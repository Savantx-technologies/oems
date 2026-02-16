<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Exam;
use App\Models\ExamAttempt;

class ExamController extends Controller
{
    public function index()
    {
        $student = Auth::user();
        $now = now();

        $exams = Exam::where('school_id', $student->school_id)
            ->where('class', $student->grade)
            ->where('status', 'published')
            ->where(function ($query) use ($now) {
                $query->whereHas('schedule', function ($q) use ($now) {
                    $q->where('end_at', '>', $now);
                })->orWhereDoesntHave('schedule');
            })
            ->with('schedule')
            ->latest()
            ->paginate(10);

        $attemptedExamIds = \App\Models\ExamAttempt::where('user_id', $student->id)
            ->pluck('exam_id')
            ->toArray();

        return view('student.exams.index', compact('exams', 'attemptedExamIds'));
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

    public function live($id)
    {
        $student = Auth::user();
        $now = now();

        $exam = Exam::where('school_id', $student->school_id)
            ->where('id', $id)
            ->where('status', 'published')
            ->with(['schedule'])
            ->firstOrFail();

        // 1. Strict Schedule Validation
        if (!$exam->schedule || !$now->between($exam->schedule->start_at, $exam->schedule->end_at)) {
            return redirect()->route('student.exams.index')->with('error', 'This exam is not currently live.');
        }

        // 2. Lifecycle Management: Create or Retrieve Attempt (Safe method)
        $attempt = \App\Models\ExamAttempt::firstOrNew([
            'user_id' => $student->id,
            'exam_id' => $exam->id,
        ]);

        if (!$attempt->exists) {
            $attempt->school_id = $student->school_id;
            $attempt->started_at = $now;
            $attempt->status = 'in_progress';
            $attempt->expires_at = $now->copy()->addMinutes($exam->duration_minutes);
            $attempt->ip_address = request()->ip();
            $attempt->save();
        }

        // 3. Check Status
        if ($attempt->submitted_at) {
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

        $questions = \App\Models\Question::whereIn('id', is_array($finalQuestionIds) ? $finalQuestionIds : [])->get();

        // Sort questions to match the persisted order
        $questions = $questions->sortBy(function ($q) use ($finalQuestionIds) {
            return array_search($q->id, $finalQuestionIds);
        })->values();

        // Prepare data for JS frontend
        $questionsData = $questions->map(function ($q) {
            $options = [];
            if ($q->option_a) $options[] = ['id' => 'A', 'text' => $q->option_a];
            if ($q->option_b) $options[] = ['id' => 'B', 'text' => $q->option_b];
            if ($q->option_c) $options[] = ['id' => 'C', 'text' => $q->option_c];
            if ($q->option_d) $options[] = ['id' => 'D', 'text' => $q->option_d];

            return [
                'id' => $q->id,
                'text' => $q->question_text,
                'marks' => $q->marks,
                'options' => $options
            ];
        })->values();

        return view('student.exams.live', compact('exam', 'questionsData', 'remainingSeconds', 'sessionToken'));
    }

    public function submit(Request $request, $id)
    {
        $student = Auth::user();
        $exam = Exam::findOrFail($id);

        $attempt = \App\Models\ExamAttempt::where('user_id', $student->id)
            ->where('exam_id', $exam->id)
            ->first();

        // 1. Validate Attempt Ownership & Status
        if (!$attempt || $attempt->submitted_at) {
            return redirect()->route('student.exams.index')->with('error', 'Invalid submission or exam already submitted.');
        }

        // 2. Validate Session Token (Prevent multiple tabs)
        if ($request->input('session_token') !== $attempt->session_token) {
            return redirect()->route('student.exams.index')->with('error', 'Session expired. You may have opened the exam in another tab.');
        }

        // 3. Secure Time Validation (Server-Side)
        $now = now();
        $startTime = $attempt->started_at;
        $durationSeconds = ($exam->duration_minutes * 60) + ($attempt->extra_time_seconds ?? 0);
        // Allow 2 minutes buffer for network latency
        $allowedEndTime = $startTime->copy()->addSeconds($durationSeconds + 120);

        if ($now->greaterThan($allowedEndTime)) {
            $attempt->submitted_at = $now;
            $attempt->status = 'expired';
            $attempt->save();
            return redirect()->route('student.exams.index')->with('error', 'Submission rejected: Exam time exceeded.');
        }

        $answers = $request->input('answers', []);

        // If it's a string (JSON or comma separated), decode it
        if (is_string($answers)) {
            $decoded = json_decode($answers, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $answers = $decoded;
            } else {
                $answers = []; // Invalid format, treat as empty
            }
        }

        if (!is_array($answers)) {
            $answers = [];
        }

        // 3. Validate Questions Belong to Exam
        $validQuestionIds = $exam->selected_questions;
        if (is_string($validQuestionIds)) {
            $validQuestionIds = json_decode($validQuestionIds, true) ?? [];
        }

        // Fetch only valid questions from DB to prevent tampering
        $questions = \App\Models\Question::whereIn('id', $validQuestionIds)->get();

        $totalQuestions = $questions->count();
        $totalCorrect = 0;

        // 4. Atomic Transaction for Data Integrity
        DB::beginTransaction();
        try {
            foreach ($questions as $question) {
                $selectedOption = $answers[$question->id] ?? null;

                // Validate option is allowed (A, B, C, D)
                if ($selectedOption !== null && !in_array($selectedOption, ['A', 'B', 'C', 'D'])) {
                    $selectedOption = null;
                }

                if ($selectedOption === null) continue;

                $isCorrect = ($question->correct_option === $selectedOption) ? 1 : 0;

                if ($isCorrect) {
                    $totalCorrect++;
                }

                \App\Models\UserExamAnswer::forceCreate([
                    'school_id' => $student->school_id,
                    'attempt_id' => $attempt->id,
                    'user_id' => $student->id,
                    'exam_id' => $exam->id,
                    'question_id' => $question->id,
                    'selected_option' => $selectedOption,
                    'is_correct' => $isCorrect,
                ]);
            }

            // Update attempt with correct count and score
            $score = $totalQuestions > 0 ? ($totalCorrect / $totalQuestions) * 100 : 0;

            $attempt->total_questions = $totalQuestions;
            $attempt->total_correct = $totalCorrect;
            $attempt->score = $score;
            $attempt->submitted_at = now();
            $attempt->status = 'submitted';
            $attempt->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while submitting. Please try again.');
        }

        return redirect()->route('student.exams.index')->with('success', 'Exam submitted successfully!');
    }

    public function logViolation(Request $request, $id)
    {
        $student = Auth::user();
        // Verify exam exists
        $exam = Exam::findOrFail($id);

        // Find active attempt
        $attempt = \App\Models\ExamAttempt::where('user_id', $student->id)
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

    public function signal(Request $request, $id)
    {
        $student = Auth::user();
        $attempt = ExamAttempt::where('user_id', $student->id)->where('exam_id', $id)->firstOrFail();

        if ($request->type === 'offer') {
            DB::table('exam_streams')->updateOrInsert(
                ['attempt_id' => $attempt->id],
                ['offer' => $request->payload, 'updated_at' => now()]
            );
            return response()->json(['status' => 'offer_stored']);
        }

        if ($request->type === 'student_ice') {
            DB::table('exam_streams')->updateOrInsert(
                ['attempt_id' => $attempt->id],
                [
                    'student_ice_candidates' => DB::raw("CONCAT(IFNULL(student_ice_candidates,''), '" . addslashes($request->payload) . "||')"),
                    'updated_at' => now()
                ]
            );
            return response()->json(['status' => 'ice_stored']);
        }

        if ($request->type === 'get_answer') {
            $stream = DB::table('exam_streams')->where('attempt_id', $attempt->id)->first();
            return response()->json([
                'answer' => $stream ? $stream->answer : null
            ]);
        }

        return response()->json(['status' => 'invalid_type'], 400);
    }
}

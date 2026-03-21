<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Exam;
use App\Models\Question;
use App\Support\ExamPayloadCache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth('admin')->user();

        // Auto-close expired exams (single query)
        Exam::where('school_id', $admin->school_id)
            ->where('status', 'published')
            ->whereHas('schedule', function ($q) {
                $q->where('end_at', '<=', now());
            })
            ->update(['status' => 'closed']);

        // Now fetch paginated exams
        $query = Exam::with('schedule')
            ->where('school_id', $admin->school_id);

        if (in_array($admin->role, [Admin::ROLE_INVIGILATOR, Admin::ROLE_STAFF], true)) {
            $query->whereHas('monitorBlocks', function ($blockQuery) use ($admin) {
                $blockQuery->where('assignee_type', Admin::class)
                    ->where('assignee_id', $admin->id);
            });
        }

        if ($request->filled('filter')) {
            $now = now();
            switch ($request->filter) {
                case 'live':
                    $query->where('status', 'published')
                        ->whereHas('schedule', function ($q) use ($now) {
                            $q->where('start_at', '<=', $now)
                                ->where('end_at', '>=', $now);
                        });
                    break;
                case 'upcoming':
                    $query->where('status', 'published')
                        ->whereHas('schedule', function ($q) use ($now) {
                            $q->where('start_at', '>', $now);
                        });
                    break;
                case 'closed':
                    $query->where('status', 'closed');
                    break;
            }
        }

        $exams = $query->latest()->paginate(20);

        return view('admin.exams.index', compact('exams'));
    }



    public function create(Request $request)
    {
        $admin = auth('admin')->user();

        $classes = Question::where('school_id', $admin->school_id)
            ->select('class')
            ->distinct()
            ->orderBy('class')
            ->pluck('class');

        $subjects = Question::where('school_id', $admin->school_id)
            ->select('subject')
            ->distinct()
            ->orderBy('subject')
            ->pluck('subject');

        //  Fetch last exam (for auto-fill)
        $lastExam = Exam::where('school_id', $admin->school_id)
            ->latest()
            ->first();

        return view('admin.exams.create', compact(
            'classes',
            'subjects',
            'lastExam'
        ));
    }

    public function store(Request $request)
    {
        // If new class entered, override dropdown
        if ($request->filled('new_class')) {
            $request->merge(['class' => $request->new_class]);
        }

        // If new subject entered, override dropdown
        if ($request->filled('new_subject')) {
            $request->merge(['subject' => $request->new_subject]);
        }
        $request->validate([
            'title' => 'required',
            'class' => 'required',
            'subject' => 'required',
            'academic_session' => 'required',
            'exam_type' => 'required',
            'duration_minutes' => 'required|integer|min:1',
        ]);

        $admin = auth('admin')->user();

        $exam = Exam::create([
            'school_id' => $admin->school_id,
            'created_by' => $admin->id,
            'title' => $request->title,
            'class' => $request->class,
            'subject' => $request->subject,
            'academic_session' => $request->academic_session,
            'exam_type' => $request->exam_type,
            'duration_minutes' => $request->duration_minutes,
            'pass_marks' => $request->pass_marks,
            'negative_marking' => $request->negative_marking ?? 0,
            'negative_marks' => $request->negative_marks ?? 0,
            'shuffle_questions' => $request->shuffle_questions ?? 0,
            'shuffle_options' => $request->shuffle_options ?? 0,
            'instructions' => json_encode(
                array_values(array_filter($request->instructions ?? []))
            ),
            'status' => 'draft'
        ]);

        return redirect()->route('admin.exams.questions', $exam->id);
    }

    // professional attach screen
    public function questions($id)
    {
        $admin = auth('admin')->user();

        $exam = Exam::where('school_id', $admin->school_id)
            ->findOrFail($id);

        $questions = Question::where('school_id', $admin->school_id)
            ->where('class', $exam->class)
            ->where('subject', $exam->subject)
            ->get();

        $attached = $exam->selected_questions ?? [];

        return view('admin.exams.questions', compact(
            'exam',
            'questions',
            'attached'
        ));
    }
    public function attachQuestions(Request $request, $id)
    {
        $admin = auth('admin')->user();

        $exam = Exam::where('school_id', $admin->school_id)
            ->findOrFail($id);

        $request->validate([
            'questions' => 'required|array'
        ]);

        $questions = Question::whereIn('id', $request->questions)
            ->where('school_id', $admin->school_id)
            ->where('class', $exam->class)
            ->where('subject', $exam->subject)
            ->get();

        $exam->update([
            'selected_questions' => $questions->pluck('id')->values()->toArray(),
            'total_marks' => $questions->sum('marks'),
        ]);

        ExamPayloadCache::forget($exam->id);

        if ($exam->exam_type === 'mock') {

            $exam->update([
                'status' => 'published'
            ]);

            ExamPayloadCache::warm($exam->fresh());

            return redirect()
                ->route('admin.exams.index')
                ->with('success', 'Mock exam published successfully.');
        }

        return redirect()->route('admin.exams.schedule', $exam->id);
    }
    public function publish($id)
    {
        $admin = auth('admin')->user();

        $exam = Exam::where('school_id', $admin->school_id)
            ->findOrFail($id);

        if (empty($exam->selected_questions) || count($exam->selected_questions) === 0) {
            return back()->with('error', 'Attach questions first');
        }
        if ($exam->exam_type !== 'mock' && !$exam->schedule) {
            return back()->with('error', 'Schedule exam first');
        }

        if (!$exam->schedule) {
            return back()->with('error', 'Schedule exam first');
        }

        $exam->update([
            'status' => 'published'
        ]);

        ExamPayloadCache::warm($exam->fresh());

        return back();
    }

    public function close($id)
    {
        $admin = auth('admin')->user();

        $exam = Exam::where('school_id', $admin->school_id)->findOrFail($id);

        $exam->update(['status' => 'closed']);
        ExamPayloadCache::forget($exam->id);

        return back();
    }

    public function show(Request $request, $id)
    {
        $admin = auth('admin')->user();

        $exam = Exam::with(['schedule', 'monitorBlocks.assignee', 'monitorBlocks.attempts.user'])
            ->where('school_id', $admin->school_id)
            ->findOrFail($id);

        // already array because of cast
        $ids = $exam->selected_questions ?? [];

        $questions = collect();

        if (!empty($ids)) {

            // keep same order as selected in exam
            $idsString = implode(',', $ids);

            $questions = Question::whereIn('id', $ids)
                ->orderByRaw("FIELD(id, $idsString)")
                ->get();
        }

        // since you removed sets & pivot
        $set = 'A';
        $sets = collect(['A']);

        $attemptOptions = $exam->attempts()
            ->with(['user', 'monitorBlock'])
            ->latest()
            ->get();

        $assignableMonitors = Admin::where('school_id', $admin->school_id)
            ->whereIn('role', [
                Admin::ROLE_SCHOOL_ADMIN,
                Admin::ROLE_SUB_ADMIN,
                Admin::ROLE_INVIGILATOR,
                Admin::ROLE_STAFF,
            ])
            ->orderBy('name')
            ->get();

        return view('admin.exams.show', compact(
            'exam',
            'questions',
            'set',
            'sets',
            'attemptOptions',
            'assignableMonitors'
        ));
    }

    public function edit($id)
    {
        $admin = auth('admin')->user();

        $exam = Exam::where('school_id', $admin->school_id)
            ->findOrFail($id);

        if ($exam->status === 'closed') {
            return redirect()
                ->route('admin.exams.index')
                ->with('error', 'Closed exam cannot be edited.');
        }

        return view('admin.exams.edit', compact('exam'));
    }

    public function update(Request $request, $id)
    {
        $admin = auth('admin')->user();

        $exam = Exam::where('school_id', $admin->school_id)
            ->findOrFail($id);

        if ($exam->status === 'closed') {
            return redirect()
                ->route('admin.exams.index')
                ->with('error', 'Closed exam cannot be edited.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'academic_session' => 'required|string',
            'exam_type' => 'required|string',
            'duration_minutes' => 'required|integer|min:1',
            'pass_marks' => 'nullable|integer|min:0',
            'negative_marks' => 'nullable|numeric|min:0',
        ]);

        $exam->update([
            'title' => $request->title,
            'academic_session' => $request->academic_session,
            'exam_type' => $request->exam_type,
            'duration_minutes' => $request->duration_minutes,
            'pass_marks' => $request->pass_marks ?? 0,

            'negative_marking' => $request->has('negative_marking'),
            'negative_marks' => $request->negative_marks ?? 0,
            'shuffle_questions' => $request->has('shuffle_questions'),
            'shuffle_options' => $request->has('shuffle_options'),

            'instructions' => json_encode(
                array_values(array_filter($request->instructions ?? []))
            ),
        ]);

        if ($exam->status === 'published') {
            ExamPayloadCache::warm($exam->fresh());
        } else {
            ExamPayloadCache::forget($exam->id);
        }

        return redirect()
            ->route('admin.exams.show', $exam->id)
            ->with('success', 'Exam updated successfully.');
    }

    public function practice()
    {
        $admin = auth('admin')->user();

        $exams = Exam::where('school_id', $admin->school_id)
            ->where('exam_type', 'mock')
            // ->where('status', 'published')
            ->latest()
            ->paginate(20);

        return view('admin.exams.practice', compact('exams'));
    }

    public function solution(Exam $exam)
    {
        $admin = auth('admin')->user();

        abort_if($exam->school_id !== $admin->school_id, 403);

        // Ensure it's practice exam
        if ($exam->exam_type !== 'mock') {
            abort(404);
        }

        $questions = Question::whereIn('id', $exam->selected_questions ?? [])
            ->get();

        return view('admin.exams.solution', compact('exam', 'questions'));
    }

    public function practiceSolutions()
    {
        $admin = auth('admin')->user();

        $exams = Exam::where('school_id', $admin->school_id)
            ->where('exam_type', 'mock')
            ->latest()
            ->paginate(20);

        return view('admin.exams.practice-solutions', compact('exams'));
    }
}

<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\School;
use App\Models\Question;
use App\Models\ExamViolation;
use App\Models\SuperAdmin;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $superAdmin = auth('superadmin')->user();
        $query = Exam::query();

        if ($superAdmin && $superAdmin->isSubSuperAdmin()) {
            $query->whereHas('monitorBlocks', function ($blockQuery) use ($superAdmin) {
                $blockQuery->where('assignee_type', SuperAdmin::class)
                    ->where('assignee_id', $superAdmin->id);
            });
        }

        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
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

        // Eager load school to display school name
        $exams = $query->with(['school', 'schedule'])
            ->latest()
            ->paginate(20);

        $schools = School::orderBy('name')->get();

        return view('superadmin.exams.index', compact('exams', 'schools'));
    }

    public function show($id)
    {
        $exam = Exam::with(['school', 'schedule', 'monitorBlocks.assignee', 'monitorBlocks.attempts.user'])->findOrFail($id);

        $questionIds = $exam->selected_questions ?? [];
        $questions = collect();

        if (!empty($questionIds)) {
            $idsString = implode(',', $questionIds);
            $questions = Question::whereIn('id', $questionIds)
                ->orderByRaw("FIELD(id, $idsString)")
                ->get();
        }

        $attempts = $exam->attempts()->with(['user', 'monitorBlock'])->latest()->paginate(20);
        $attemptOptions = $exam->attempts()->with(['user', 'monitorBlock'])->latest()->get();
        $assignableSubSuperAdmins = SuperAdmin::where('role', SuperAdmin::ROLE_SUB_SUPERADMIN)
            ->where('is_active', true)
            ->orderBy('name')
            ->get()
            ->filter(fn (SuperAdmin $user) => $user->canAccessSection('live_monitoring'))
            ->values();

        return view('superadmin.exams.show', compact('exam', 'questions', 'attempts', 'attemptOptions', 'assignableSubSuperAdmins'));
    }

    public function forceClose($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->update(['status' => 'closed']);

        return back()->with('success', 'Exam force closed successfully.');
    }

    public function violationSummary(Request $request)
    {
        $query = ExamViolation::with(['user', 'attempt.exam.school']);

        if ($request->filled('school_id')) {
            $query->whereHas('attempt.exam', function($q) use ($request) {
                $q->where('school_id', $request->school_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('admission_number', 'like', "%{$search}%");
                })->orWhereHas('attempt.exam', function($q3) use ($search) {
                    $q3->where('title', 'like', "%{$search}%");
                })->orWhereHas('attempt.exam.school', function($q4) use ($search) {
                    $q4->where('name', 'like', "%{$search}%");
                });
            });
        }

        $violations = $query->latest()->paginate(20);
        $schools = School::orderBy('name')->get();

        return view('superadmin.exams.violation_summary', compact('violations', 'schools'));
    }
}

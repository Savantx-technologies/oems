<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Admin;
use App\Models\User;
use App\Models\Exam;
use App\Models\ExamAttempt;

class ReportController extends Controller
{
    public function index()
    {
        return redirect()->route('superadmin.reports.analytics');
    }

    public function analytics()
    {
        $stats = [
            'total_schools' => School::count(),
            'total_admins' => Admin::count(),
            'total_students' => User::count(),
            'total_exams' => Exam::count(),
            'live_exams' => Exam::where('status', 'published')
                                ->whereHas('schedule', function ($q) {
                                    $q->where('start_at', '<=', now())
                                      ->where('end_at', '>=', now());
                                })->count(),
            'total_attempts' => ExamAttempt::count(),
            'avg_score' => ExamAttempt::avg('score') ?? 0,
        ];

        // Top 5 Schools by Exam Attempts
        $topSchools = School::withCount('attempts')
            ->orderByDesc('attempts_count')
            ->take(5)
            ->get();

        // Recent 5 Exams
        $recentExams = Exam::with('school')->withCount('attempts')
            ->latest()->take(5)->get();

        return view('superadmin.reports.analytics', compact('stats', 'topSchools', 'recentExams'));
    }

    public function exams(Request $request)
    {
        $query = Exam::with(['school', 'attempts']);

        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $exams = $query->latest()->paginate(20);
        $schools = School::orderBy('name')->get();

        return view('superadmin.reports.exams', compact('exams', 'schools'));
    }

    public function violations()
    {
        return redirect()->route('superadmin.exams.violation-summary');
    }

    public function schools(Request $request)
    {
        $schools = School::withCount(['admins', 'students', 'exams', 'attempts'])
            ->withAvg('attempts', 'score')
            ->orderBy('name')
            ->paginate(20);

        return view('superadmin.reports.schools', compact('schools'));
    }
}

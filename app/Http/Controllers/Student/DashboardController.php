<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Exam;
use App\Models\ExamAttempt;

class DashboardController extends Controller
{
    public function index()
    {
        $student = Auth::user();
        $now = now();

        $upcomingExams = Exam::where('school_id', $student->school_id)
            ->where('class', $student->grade)
            ->where('status', 'published')
            ->where(function ($query) use ($now) {
                $query->whereHas('schedule', function ($q) use ($now) {
                    $q->where('end_at', '>', $now);
                })->orWhereDoesntHave('schedule');
            })
            ->with(['schedule', 'attempts' => function ($q) use ($student) {
                $q->where('user_id', $student->id);
            }])
            ->latest()
            ->get();

        // Statistics
        $attempts = ExamAttempt::where('user_id', $student->id)
            ->whereNotNull('submitted_at')
            ->get();

        $completedExamsCount = $attempts->count();
        $averageScore = $attempts->avg('score') ?? 0;

        // Recent Results
        $recentResults = ExamAttempt::with('exam')
            ->where('user_id', $student->id)
            ->whereNotNull('submitted_at')
            ->latest('submitted_at')
            ->take(5)
            ->get();

        return view('student.dashboard', compact('upcomingExams', 'completedExamsCount', 'averageScore', 'recentResults'));
    }

    public function systemCheck()
    {
        return view('student.system_check');
    }
}

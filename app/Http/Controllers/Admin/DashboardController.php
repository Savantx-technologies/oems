<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $admin = auth('admin')->user();
        $schoolId = $admin->school_id;

        // Total Students
        $totalStudents = User::where('school_id', $schoolId)->where('role', 'student')->count();

        // Upcoming Exams
        $upcomingExams = Exam::where('school_id', $schoolId)
            ->where('status', 'published')
            ->whereHas('schedule', function ($query) {
                $query->where('start_at', '>', now());
            })
            ->count();

        // Live Exams
        $liveExams = Exam::where('school_id', $schoolId)
            ->where('status', 'published')
            ->whereHas('schedule', function ($query) {
                $query->where('start_at', '<=', now())
                    ->where('end_at', '>=', now());
            })
            ->count();

        // Get exam IDs for the school
        $examIds = Exam::where('school_id', $schoolId)->pluck('id');

        // Pending Evaluations: Count of attempts that are completed/submitted
        $pendingEvaluations = ExamAttempt::whereIn('exam_id', $examIds)
            ->whereIn('status', ['completed', 'submitted'])->count();

        // Violation Alerts: Count of violations in the last 24 hours
        $violationAlerts = DB::table('exam_violations')
            ->whereIn('attempt_id', function ($query) use ($examIds) {
                $query->select('id')->from('exam_attempts')->whereIn('exam_id', $examIds);
            })
            ->where('created_at', '>=', now()->subDay())
            ->count();

        // Recent Activity: Last 5 exam attempts
        $recentActivities = ExamAttempt::with('user', 'exam')
            ->whereIn('exam_id', $examIds)
            ->latest('updated_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'upcomingExams',
            'liveExams',
            'pendingEvaluations',
            'violationAlerts',
            'recentActivities'
        ));
    }
}

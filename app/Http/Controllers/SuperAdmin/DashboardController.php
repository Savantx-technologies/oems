<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminRequest;
use App\Models\Exam;
use App\Models\School;
use App\Models\SecurityLog;
use App\Models\StaffRequest;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats Cards
        $totalSchools = School::count();
        $newSchools = School::where('created_at', '>=', now()->subMonth())->count();
        $totalSchoolsPrevious = $totalSchools - $newSchools;
        $schoolGrowthPercentage = $totalSchoolsPrevious > 0 ? ($newSchools / $totalSchoolsPrevious) * 100 : ($newSchools > 0 ? 100 : 0);

        $totalAdmins = Admin::whereIn('role', ['school_admin', 'sub_admin', 'staff'])->count();
        $totalStudents = User::where('role', 'student')->count();
        $newStudents = User::where('role', 'student')->where('created_at', '>=', now()->subMonth())->count();
        $totalStudentsPrevious = $totalStudents - $newStudents;
        $studentGrowthPercentage = $totalStudentsPrevious > 0 ? ($newStudents / $totalStudentsPrevious) * 100 : ($newStudents > 0 ? 100 : 0);

        $liveExams = Exam::where('status', 'published')
            ->whereHas('schedule', function ($query) {
                $query->where('start_at', '<=', now())
                    ->where('end_at', '>=', now());
            })
            ->count();

        $pendingStaffRequests = StaffRequest::where('status', 'pending_verification')->count();
        $pendingAdminRequests = AdminRequest::where('status', 'pending')->count();
        $pendingApprovals = $pendingStaffRequests + $pendingAdminRequests;

        $systemAlerts = 0; // Placeholder - implement your alert logic here.

        $recentActivities = SecurityLog::with('user')->latest()->limit(5)->get();

        return view('superadmin.dashboard', compact(
            'totalSchools', 'schoolGrowthPercentage',
            'totalAdmins',
            'totalStudents', 'studentGrowthPercentage',
            'liveExams', 'pendingApprovals', 'systemAlerts',
            'recentActivities'
        ));
    }
}

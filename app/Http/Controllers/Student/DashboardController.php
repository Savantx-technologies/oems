<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Exam;

class DashboardController extends Controller
{
    public function index()
    {
        $student = Auth::user();

        $upcomingExams = Exam::where('school_id', $student->school_id)
            ->where('class', $student->grade)
            ->where('status', 'published')
            ->latest()
            ->get();

        return view('student.dashboard', compact('upcomingExams'));
    }

    public function systemCheck()
    {
        return view('student.system_check');
    }
}

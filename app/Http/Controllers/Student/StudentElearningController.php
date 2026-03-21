<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Elearning;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class StudentElearningController extends Controller
{
    public function index()
    {
        $student = Auth::user();
        $studentGrade = trim($student->grade ?? '');

        $classes = collect(); 
        if ($studentGrade) {
            $hasClassContent = Elearning::where('class_id', $studentGrade)->exists();

            if ($hasClassContent) {
               
                $classes = collect([(object) ['class_id' => $studentGrade]]);
            }
        }

        return view('student.elearning.index', compact('classes'));
    }

    public function classLessons($class)
    {
        $student = Auth::user();
        $studentGrade = trim($student->grade ?? '');

        // Security check: student can only access their own class lessons.
        if ($studentGrade !== trim($class)) {
            abort(403, 'You are not authorized to view this class content.');
        }

        $lessons = Elearning::where('class_id', $class)->latest()->get();

        return view('student.elearning.lessons', compact('lessons', 'class'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;

class ExamRulesController extends Controller
{
    public function edit()
    {
        $admin = auth('admin')->user();
        $school = $admin->school;
        
        if (!$school && $admin->school_id) {
             $school = School::find($admin->school_id);
        }

        $rules = $school->exam_rules ?? [];
        
        // Handle case where it might be a JSON string if not cast in model
        if (is_string($rules)) {
            $rules = json_decode($rules, true) ?? [];
        }

        return view('admin.settings.exam_rules', compact('school', 'rules'));
    }

    public function update(Request $request)
    {
        $admin = auth('admin')->user();
        $school = $admin->school;

        if (!$school && $admin->school_id) {
             $school = School::find($admin->school_id);
        }

        $validated = $request->validate([
            'passing_percentage' => 'required|integer|min:0|max:100',
            'default_duration' => 'required|integer|min:1',
            'result_release_mode' => 'required|in:immediate,manual',
            'shuffle_questions' => 'nullable|in:on,1,true',
            'disable_copy_paste' => 'nullable|in:on,1,true',
            'full_screen_mode' => 'nullable|in:on,1,true',
            'default_instructions' => 'nullable|string',
        ]);

        // Save directly; ensure 'exam_rules' is cast to 'array' in School model or handle json_encode here if needed
        $school->exam_rules = $validated;
        $school->save();

        return back()->with('success', 'Exam rules updated successfully.');
    }
}

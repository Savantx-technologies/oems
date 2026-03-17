<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Setting;
use Illuminate\Http\Request;

class ExamRulesController extends Controller
{
    public function edit()
    {
        $rules = Setting::where('key', 'default_exam_rules')->value('value') ?? [];

        if (is_string($rules)) {
            $rules = json_decode($rules, true) ?? [];
        }

        $stats = [
            'total_schools' => School::count(),
            'schools_with_custom_rules' => School::whereNotNull('exam_rules')->count(),
            'schools_using_defaults' => School::whereNull('exam_rules')->count(),
        ];

        return view('superadmin.settings.exam-rules', compact('rules', 'stats'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'passing_percentage' => 'required|integer|min:0|max:100',
            'default_duration' => 'required|integer|min:1|max:1440',
            'result_release_mode' => 'required|in:immediate,manual',
            'shuffle_questions' => 'nullable|in:on,1,true',
            'disable_copy_paste' => 'nullable|in:on,1,true',
            'full_screen_mode' => 'nullable|in:on,1,true',
            'default_instructions' => 'nullable|string',
        ]);

        $rules = [
            'passing_percentage' => (int) $validated['passing_percentage'],
            'default_duration' => (int) $validated['default_duration'],
            'result_release_mode' => $validated['result_release_mode'],
            'shuffle_questions' => $request->boolean('shuffle_questions'),
            'disable_copy_paste' => $request->boolean('disable_copy_paste'),
            'full_screen_mode' => $request->boolean('full_screen_mode'),
            'default_instructions' => $validated['default_instructions'] ?? null,
        ];

        Setting::updateOrCreate(
            ['key' => 'default_exam_rules'],
            ['value' => $rules]
        );

        return back()->with('success', 'Exam rules engine updated successfully.');
    }
}

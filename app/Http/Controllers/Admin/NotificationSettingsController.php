<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;

class NotificationSettingsController extends Controller
{
    public function edit()
    {
        $admin = auth('admin')->user();
        $school = $admin->school;
        
        if (!$school && $admin->school_id) {
             $school = School::find($admin->school_id);
        }

        $settings = $school->notification_settings ?? [];
        
        if (is_string($settings)) {
            $settings = json_decode($settings, true) ?? [];
        }

        return view('admin.settings.notifications', compact('school', 'settings'));
    }

    public function update(Request $request)
    {
        $admin = auth('admin')->user();
        $school = $admin->school;

        if (!$school && $admin->school_id) {
             $school = School::find($admin->school_id);
        }

        $validated = $request->validate([
            'email_alerts' => 'nullable|in:on,1,true',
            'sms_alerts' => 'nullable|in:on,1,true',
            'exam_published' => 'nullable|in:on,1,true',
            'result_released' => 'nullable|in:on,1,true',
            'student_login_alert' => 'nullable|in:on,1,true',
        ]);

        $school->notification_settings = $validated;
        $school->save();

        return back()->with('success', 'Notification settings updated successfully.');
    }
}

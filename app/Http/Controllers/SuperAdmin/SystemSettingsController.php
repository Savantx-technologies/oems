<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;

class SystemSettingsController extends Controller
{
    public function edit()
    {
        $settingsRaw = Setting::all()->keyBy('key');
        $settings = $settingsRaw->map(fn($setting) => $setting->value);

        // Provide defaults if not set
        $settings['default_exam_rules'] = $settings['default_exam_rules'] ?? [];

        return view('superadmin.settings.system', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'sometimes|required|string|max:255',
            'app_logo' => 'nullable|image|max:2048',
            'app_favicon' => 'nullable|image|mimes:ico,png|max:512',
            
            // Email Settings
            'mail_mailer' => 'nullable|string|max:50',
            'mail_host' => 'nullable|string|max:255',
            'mail_port' => 'nullable|numeric',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|string|max:50',
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name' => 'nullable|string|max:255',

            'exam_rules' => 'sometimes|required|array',
            'exam_rules.passing_percentage' => 'required_with:exam_rules|integer|min:0|max:100',
            'exam_rules.default_duration' => 'required_with:exam_rules|integer|min:1',
            'exam_rules.result_release_mode' => 'required_with:exam_rules|in:immediate,manual',
            'exam_rules.shuffle_questions' => 'nullable|string',
            'exam_rules.disable_copy_paste' => 'nullable|string',
            'exam_rules.full_screen_mode' => 'nullable|string',
            'exam_rules.default_instructions' => 'nullable|string',
        ]);

        $textSettings = [
            'app_name',
            'mail_mailer', 'mail_host', 'mail_port', 'mail_username', 
            'mail_encryption', 'mail_from_address', 'mail_from_name'
        ];

        foreach ($textSettings as $key) {
            if ($request->has($key)) {
                Setting::updateOrCreate(['key' => $key], ['value' => $request->input($key)]);
            }
        }

        if ($request->filled('mail_password')) {
            Setting::updateOrCreate(['key' => 'mail_password'], ['value' => $request->input('mail_password')]);
        }

        if ($request->hasFile('app_logo')) {
            $current = Setting::where('key', 'app_logo')->value('value');
            if ($current && Storage::disk('public')->exists($current)) {
                Storage::disk('public')->delete($current);
            }
            $path = $request->file('app_logo')->store('system', 'public');
            Setting::updateOrCreate(['key' => 'app_logo'], ['value' => $path]);
        }
        
        if ($request->hasFile('app_favicon')) {
            $current = Setting::where('key', 'app_favicon')->value('value');
            if ($current && Storage::disk('public')->exists($current)) {
                Storage::disk('public')->delete($current);
            }
            $path = $request->file('app_favicon')->store('system', 'public');
            Setting::updateOrCreate(['key' => 'app_favicon'], ['value' => $path]);
        }

        if ($request->has('exam_rules')) {
            $examRules = $request->input('exam_rules');
            $examRules['shuffle_questions'] = $request->has('exam_rules.shuffle_questions');
            $examRules['disable_copy_paste'] = $request->has('exam_rules.disable_copy_paste');
            $examRules['full_screen_mode'] = $request->has('exam_rules.full_screen_mode');
            Setting::updateOrCreate(['key' => 'default_exam_rules'], ['value' => $examRules]);
        }

        return back()->with('success', 'System settings updated successfully.');
    }

    public function sendTestMail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            Mail::to($request->test_email)->send(new TestMail());
            return back()->with('success', 'Test email sent successfully to ' . $request->test_email);
        } catch (\Exception $e) {
            // It's better to return a specific error message
            $errorMessage = 'Failed to send test email. Please check your SMTP settings and try again. Error: ' . $e->getMessage();
            return back()->with('error', $errorMessage);
        }
    }
}

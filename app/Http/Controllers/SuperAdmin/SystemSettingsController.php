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
    private const PROCTORING_SETTINGS_KEY = 'superadmin_proctoring_settings';
    private const CAMERA_RULES_KEY = 'superadmin_camera_rules';
    private const ANTI_CHEAT_SETTINGS_KEY = 'superadmin_anti_cheat_settings';
    private const NOTIFICATION_TEMPLATES_KEY = 'superadmin_notification_templates';

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

    public function editProctoring()
    {
        $settings = array_merge([
            'enable_ai_proctoring' => true,
            'face_presence_required' => true,
            'detect_multiple_faces' => true,
            'auto_warn_student' => true,
            'auto_pause_exam' => false,
            'incident_review_required' => true,
            'warning_limit' => 3,
            'confidence_threshold' => 80,
        ], $this->getArraySetting(self::PROCTORING_SETTINGS_KEY));

        return view('superadmin.settings.proctoring-settings', compact('settings'));
    }

    public function updateProctoring(Request $request)
    {
        $validated = $request->validate([
            'warning_limit' => 'required|integer|min:0|max:20',
            'confidence_threshold' => 'required|integer|min:50|max:100',
        ]);

        $settings = array_merge($validated, [
            'enable_ai_proctoring' => $request->boolean('enable_ai_proctoring'),
            'face_presence_required' => $request->boolean('face_presence_required'),
            'detect_multiple_faces' => $request->boolean('detect_multiple_faces'),
            'auto_warn_student' => $request->boolean('auto_warn_student'),
            'auto_pause_exam' => $request->boolean('auto_pause_exam'),
            'incident_review_required' => $request->boolean('incident_review_required'),
        ]);

        $this->saveArraySetting(self::PROCTORING_SETTINGS_KEY, $settings);

        return back()->with('success', 'Proctoring settings updated successfully.');
    }

    public function editCameraRules()
    {
        $settings = array_merge([
            'require_camera' => true,
            'allow_mobile_camera' => false,
            'capture_snapshots' => true,
            'block_virtual_cameras' => true,
            'camera_permission_retry' => true,
            'minimum_resolution' => '720p',
            'snapshot_interval_seconds' => 30,
        ], $this->getArraySetting(self::CAMERA_RULES_KEY));

        return view('superadmin.settings.camera-rules', compact('settings'));
    }

    public function updateCameraRules(Request $request)
    {
        $validated = $request->validate([
            'minimum_resolution' => 'required|in:480p,720p,1080p',
            'snapshot_interval_seconds' => 'required|integer|min:5|max:300',
        ]);

        $settings = array_merge($validated, [
            'require_camera' => $request->boolean('require_camera'),
            'allow_mobile_camera' => $request->boolean('allow_mobile_camera'),
            'capture_snapshots' => $request->boolean('capture_snapshots'),
            'block_virtual_cameras' => $request->boolean('block_virtual_cameras'),
            'camera_permission_retry' => $request->boolean('camera_permission_retry'),
        ]);

        $this->saveArraySetting(self::CAMERA_RULES_KEY, $settings);

        return back()->with('success', 'Camera rules updated successfully.');
    }

    public function editAntiCheat()
    {
        $settings = array_merge([
            'enforce_full_screen' => true,
            'disable_copy_paste' => true,
            'disable_right_click' => true,
            'detect_tab_switches' => true,
            'detect_multiple_displays' => false,
            'block_developer_tools' => true,
            'max_tab_switches' => 2,
        ], $this->getArraySetting(self::ANTI_CHEAT_SETTINGS_KEY));

        return view('superadmin.settings.anti-cheat-settings', compact('settings'));
    }

    public function updateAntiCheat(Request $request)
    {
        $validated = $request->validate([
            'max_tab_switches' => 'required|integer|min:0|max:20',
        ]);

        $settings = array_merge($validated, [
            'enforce_full_screen' => $request->boolean('enforce_full_screen'),
            'disable_copy_paste' => $request->boolean('disable_copy_paste'),
            'disable_right_click' => $request->boolean('disable_right_click'),
            'detect_tab_switches' => $request->boolean('detect_tab_switches'),
            'detect_multiple_displays' => $request->boolean('detect_multiple_displays'),
            'block_developer_tools' => $request->boolean('block_developer_tools'),
        ]);

        $this->saveArraySetting(self::ANTI_CHEAT_SETTINGS_KEY, $settings);

        return back()->with('success', 'Anti-cheat settings updated successfully.');
    }

    public function editNotificationTemplates()
    {
        $settings = array_merge([
            'email_enabled' => true,
            'dashboard_enabled' => true,
            'exam_alert_subject' => 'Exam activity alert',
            'exam_alert_body' => 'An exam-related event requires your attention. Please review the latest activity in the dashboard.',
            'violation_alert_subject' => 'Violation detected during exam',
            'violation_alert_body' => 'A new exam violation has been detected. Review the incident details and take the required action.',
            'system_alert_subject' => 'System notification',
            'system_alert_body' => 'A platform-level update has been recorded. Please review the system configuration and logs.',
        ], $this->getArraySetting(self::NOTIFICATION_TEMPLATES_KEY));

        return view('superadmin.settings.notification-templates', compact('settings'));
    }

    public function updateNotificationTemplates(Request $request)
    {
        $validated = $request->validate([
            'exam_alert_subject' => 'required|string|max:255',
            'exam_alert_body' => 'required|string',
            'violation_alert_subject' => 'required|string|max:255',
            'violation_alert_body' => 'required|string',
            'system_alert_subject' => 'required|string|max:255',
            'system_alert_body' => 'required|string',
        ]);

        $settings = array_merge($validated, [
            'email_enabled' => $request->boolean('email_enabled'),
            'dashboard_enabled' => $request->boolean('dashboard_enabled'),
        ]);

        $this->saveArraySetting(self::NOTIFICATION_TEMPLATES_KEY, $settings);

        return back()->with('success', 'Notification templates updated successfully.');
    }

    private function getArraySetting(string $key): array
    {
        $value = Setting::where('key', $key)->value('value');

        if (is_string($value)) {
            $value = json_decode($value, true) ?? [];
        }

        return is_array($value) ? $value : [];
    }

    private function saveArraySetting(string $key, array $value): void
    {
        Setting::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}

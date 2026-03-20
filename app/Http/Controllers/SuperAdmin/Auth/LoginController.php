<?php

namespace App\Http\Controllers\SuperAdmin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SuperAdmin;
use App\Models\SuperAdminOtp;
use App\Mail\SuperAdminOtpMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Support\SecurityLogger;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('superadmin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'is_active' => 1
        ];

        if (Auth::guard('superadmin')->attempt($credentials)) {

            $request->session()->regenerate();

            $admin = Auth::guard('superadmin')->user();

            SecurityLogger::log(
                'superadmin',
                $admin->id,
                'login_success',
                'Super admin logged in using password'
            );

            return redirect()->to($this->redirectPathFor($admin));
        }

        SecurityLogger::log(
            'superadmin',
            null,
            'login_failed',
            'Invalid password login attempt',
            ['email' => $request->email]
        );

        return back()->withErrors([
            'email' => 'Invalid login credentials',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        $adminId = Auth::guard('superadmin')->id();

        SecurityLogger::log(
            'superadmin',
            $adminId,
            'logout',
            'Super admin logged out'
        );

        Auth::guard('superadmin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('superadmin.login');
    }


    public function showOtpForm()
    {
        return view('superadmin.auth.otp-email');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $admin = SuperAdmin::where('email', $request->email)
            ->where('is_active', 1)
            ->first();

        if (!$admin) {
            return back()->withErrors([
                'email' => 'Account not found.'
            ]);
        }

        $otp = random_int(100000, 999999);

        SuperAdminOtp::where('super_admin_id', $admin->id)->delete();

        SuperAdminOtp::create([
            'super_admin_id' => $admin->id,
            'otp' => bcrypt($otp),
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        Mail::to($admin->email)->send(new SuperAdminOtpMail($otp));
        SecurityLogger::log(
            'superadmin',
            $admin->id,
            'otp_sent',
            'OTP sent to super admin email'
        );

        session([
            'superadmin_otp_id' => $admin->id
        ]);

        return redirect()->route('superadmin.otp.verify.form');
    }

    public function showVerifyOtpForm()
    {
        if (!session()->has('superadmin_otp_id')) {
            return redirect()->route('superadmin.otp.form');
        }

        return view('superadmin.auth.otp-verify');
    }
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        $adminId = session('superadmin_otp_id');
        if (!$adminId) {
            return redirect()->route('superadmin.otp.form');
        }
        $record = SuperAdminOtp::where('super_admin_id', $adminId)
            ->orderBy('id', 'desc')
            ->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'OTP not found.']);
        }

        if (Carbon::now()->greaterThan($record->expires_at)) {
            return back()->withErrors(['otp' => 'OTP expired.']);
        }

        if (!\Hash::check($request->otp, $record->otp)) {
            return back()->withErrors(['otp' => 'Invalid OTP.']);
        }

        $admin = SuperAdmin::findOrFail($adminId);

        auth()->guard('superadmin')->login($admin);
        SecurityLogger::log(
            'superadmin',
            $admin->id,
            'otp_login_success',
            'Super admin logged in using OTP'
        );

        session()->forget('superadmin_otp_id');
        $record->delete();

        return redirect()->to($this->redirectPathFor($admin));
    }

    private function redirectPathFor(SuperAdmin $admin): string
    {
        if ($admin->canAccessSection('dashboard')) {
            return route('superadmin.dashboard');
        }

        if ($admin->canAccessSection('live_monitoring') || $admin->canAccessSection('exams')) {
            return route('superadmin.exams.index');
        }

        if ($admin->canAccessSection('schools')) {
            return route('superadmin.schools.index');
        }

        if ($admin->canAccessSection('admins')) {
            return route('superadmin.admins.index');
        }

        if ($admin->canAccessSection('sub_superadmins')) {
            return route('superadmin.sub-superadmins.index');
        }

        if ($admin->canAccessSection('roles_permissions')) {
            return route('superadmin.roles-permissions.index');
        }

        if ($admin->canAccessSection('students')) {
            return route('superadmin.students.index');
        }

        if ($admin->canAccessSection('reports')) {
            return route('superadmin.reports.analytics');
        }

        if ($admin->canAccessSection('logs')) {
            return route('superadmin.security.logs');
        }

        if ($admin->canAccessSection('settings')) {
            return route('superadmin.settings.system');
        }

        return route('superadmin.profile');
    }
}

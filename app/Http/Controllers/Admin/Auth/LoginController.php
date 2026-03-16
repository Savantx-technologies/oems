<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Support\SecurityLogger;
use App\Models\Admin;
use App\Models\AdminOtp;
use App\Mail\AdminOtpMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    /**
     * Show admin login form
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Use the 'admin' guard
        if (Auth::guard('admin')->attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            SecurityLogger::log(
                'admin',
                auth()->guard('admin')->id(),
                'login_success',
                'Admin logged in'
            );

            return redirect()->route('admin.dashboard');
        }
        SecurityLogger::log(
            'admin',
            null,
            'login_failed',
            'Admin login failed',
            ['email' => $request->email]
        );


        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    /**
     * Logout admin
     */
    public function logout(Request $request)
    {
        $adminId = auth()->guard('admin')->id();

        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        SecurityLogger::log(
            'admin',
            $adminId,
            'logout',
            'Admin logged out'
        );

        return redirect()->route('admin.login');
    }

    public function otpForm()
    {
        if (!session()->has('admin_otp_id')) {
            return redirect()->route('admin.login');
        }

        return view('admin.auth.otp-verify');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $admin = Admin::where('email', $request->email)
            ->where('status', 'active')
            ->first();


        if (!$admin) {
            return back()->withErrors([
                'email' => 'Account not found.'
            ]);
        }

        $otp = random_int(100000, 999999);

        AdminOtp::where('admin_id', $admin->id)->delete();

        AdminOtp::create([
            'admin_id' => $admin->id,
            'otp' => bcrypt($otp),
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        Mail::to($admin->email)->send(new AdminOtpMail($otp));
        SecurityLogger::log(
            'admin',
            $admin->id,
            'otp_sent',
            'OTP sent to admin email'
        );

        session([
            'admin_otp_id' => $admin->id
        ]);

        return redirect()->route('admin.otp.verify.form');
    }

    /**
     * Verify OTP and login
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        $adminId = session('admin_otp_id');
        if (!$adminId) {
            return redirect()->route('admin.login');
        }

        $record = AdminOtp::where('admin_id', $adminId)
            ->orderBy('id', 'desc')
            ->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'OTP not found.']);
        }

        if (Carbon::now()->greaterThan($record->expires_at)) {
            return back()->withErrors(['otp' => 'OTP expired.']);
        }

        if (!Hash::check($request->otp, $record->otp)) {
            return back()->withErrors(['otp' => 'Invalid OTP.']);
        }

        $admin = Admin::findOrFail($adminId);

        auth()->guard('admin')->login($admin);

        SecurityLogger::log(
            'admin',
            $admin->id,
            'otp_login_success',
            'Admin logged in using OTP'
        );

        session()->forget('admin_otp_id');
        $record->delete();

        return redirect()->route('admin.dashboard');
    }

}

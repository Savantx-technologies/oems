<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminOtpMail;
use App\Models\AdminOtp;
use App\Models\SecurityLog;
use App\Support\SecurityLogger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function show()
    {
        $admin = Auth::guard('admin')->user();

        $recentLogs = SecurityLog::where('guard', 'admin')
            ->where('user_id', $admin->id)
            ->latest()
            ->take(8)
            ->get();

        $otpRecord = AdminOtp::where('admin_id', $admin->id)
            ->orderBy('id', 'desc')
            ->first();

        $otpExpiresAt = $otpRecord?->expires_at;

        return view('admin.profile', compact('admin', 'recentLogs', 'otpExpiresAt'));
    }

    public function sendPasswordOtp(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($request->current_password, $admin->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'The provided password does not match your current password.',
            ]);
        }

        if (Hash::check($request->password, $admin->password)) {
            throw ValidationException::withMessages([
                'password' => 'The new password must be different from your current password.',
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

        $request->session()->put([
            'admin_password_pending_hash' => Hash::make($request->password),
            'admin_password_pending_at' => now()->timestamp,
        ]);

        SecurityLogger::log(
            'admin',
            $admin->id,
            'password_otp_sent',
            'Password change OTP sent'
        );

        return back()
            ->with('success', 'OTP sent to your email address.')
            ->with('open_password_modal', true)
            ->with('password_otp_step', 'verify');
    }

    public function resendPasswordOtp(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        if (!$request->session()->has('admin_password_pending_hash')) {
            return back()->withErrors([
                'otp' => 'Please enter your new password before requesting an OTP.',
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
            'password_otp_resent',
            'Password change OTP resent'
        );

        return back()
            ->with('success', 'A new OTP has been sent to your email address.')
            ->with('open_password_modal', true)
            ->with('password_otp_step', 'verify');
    }

    public function updatePassword(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $record = AdminOtp::where('admin_id', $admin->id)
            ->orderBy('id', 'desc')
            ->first();

        if (!$record) {
            throw ValidationException::withMessages([
                'otp' => 'OTP not found. Please request a new OTP.',
            ]);
        }

        if (Carbon::now()->greaterThan($record->expires_at)) {
            throw ValidationException::withMessages([
                'otp' => 'OTP expired. Please request a new OTP.',
            ]);
        }

        if (!Hash::check($request->otp, $record->otp)) {
            throw ValidationException::withMessages([
                'otp' => 'Invalid OTP. Please try again.',
            ]);
        }

        $pendingHash = $request->session()->get('admin_password_pending_hash');
        if (!$pendingHash) {
            throw ValidationException::withMessages([
                'otp' => 'Password change session expired. Please try again.',
            ]);
        }

        $admin->password = $pendingHash;
        $admin->save();

        $record->delete();
        $request->session()->forget(['admin_password_pending_hash', 'admin_password_pending_at']);

        SecurityLogger::log(
            'admin',
            $admin->id,
            'password_updated',
            'Admin password updated with OTP'
        );

        return redirect()
            ->route('admin.profile')
            ->with('success', 'Your password has been updated successfully.');
    }
}

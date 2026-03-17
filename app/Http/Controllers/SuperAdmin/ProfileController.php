<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Mail\SuperAdminOtpMail;
use App\Models\SecurityLog;
use App\Models\SuperAdminOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function show()
    {
        $superAdmin = Auth::guard('superadmin')->user();

        $recentLogs = SecurityLog::where('guard', 'superadmin')
            ->where('user_id', $superAdmin->id)
            ->latest()
            ->take(8)
            ->get();

        $otpRecord = SuperAdminOtp::where('super_admin_id', $superAdmin->id)
            ->orderBy('id', 'desc')
            ->first();

        $otpExpiresAt = $otpRecord?->expires_at;

        return view('superadmin.profile', compact('superAdmin', 'recentLogs', 'otpExpiresAt'));
    }

    public function updatePassword(Request $request)
    {
        $superAdmin = Auth::guard('superadmin')->user();

        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $record = SuperAdminOtp::where('super_admin_id', $superAdmin->id)
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

        $pendingHash = $request->session()->get('superadmin_password_pending_hash');
        if (!$pendingHash) {
            throw ValidationException::withMessages([
                'otp' => 'Password change session expired. Please try again.',
            ]);
        }

        $superAdmin->password = $pendingHash;
        $superAdmin->save();

        $record->delete();
        $request->session()->forget(['superadmin_password_pending_hash', 'superadmin_password_pending_at']);

        return redirect()
            ->route('superadmin.profile')
            ->with('success', 'Your password has been updated successfully.');
    }

    public function sendPasswordOtp(Request $request)
    {
        $superAdmin = Auth::guard('superadmin')->user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($request->current_password, $superAdmin->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'The provided password does not match your current password.',
            ]);
        }

        if (Hash::check($request->password, $superAdmin->password)) {
            throw ValidationException::withMessages([
                'password' => 'The new password must be different from your current password.',
            ]);
        }

        $otp = random_int(100000, 999999);

        SuperAdminOtp::where('super_admin_id', $superAdmin->id)->delete();

        SuperAdminOtp::create([
            'super_admin_id' => $superAdmin->id,
            'otp' => bcrypt($otp),
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        Mail::to($superAdmin->email)->send(new SuperAdminOtpMail($otp));

        $request->session()->put([
            'superadmin_password_pending_hash' => Hash::make($request->password),
            'superadmin_password_pending_at' => now()->timestamp,
        ]);

        return redirect()
            ->route('superadmin.profile', ['otp' => 1])
            ->with('success', 'OTP sent to your email address.')
            ->with('open_password_modal', true)
            ->with('password_otp_step', 'verify');
    }

    public function resendPasswordOtp(Request $request)
    {
        $superAdmin = Auth::guard('superadmin')->user();

        if (!$request->session()->has('superadmin_password_pending_hash')) {
            return back()->withErrors([
                'otp' => 'Please enter your new password before requesting an OTP.',
            ]);
        }

        $otp = random_int(100000, 999999);

        SuperAdminOtp::where('super_admin_id', $superAdmin->id)->delete();

        SuperAdminOtp::create([
            'super_admin_id' => $superAdmin->id,
            'otp' => bcrypt($otp),
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        Mail::to($superAdmin->email)->send(new SuperAdminOtpMail($otp));

        return redirect()
            ->route('superadmin.profile', ['otp' => 1])
            ->with('success', 'A new OTP has been sent to your email address.')
            ->with('open_password_modal', true)
            ->with('password_otp_step', 'verify');
    }
}

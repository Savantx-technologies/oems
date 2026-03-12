<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SecurityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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

        return view('superadmin.profile', compact('superAdmin', 'recentLogs'));
    }

    public function updatePassword(Request $request)
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

        $superAdmin->password = Hash::make($request->password);
        $superAdmin->save();

        return redirect()
            ->route('superadmin.profile')
            ->with('success', 'Your password has been updated successfully.');
    }
}

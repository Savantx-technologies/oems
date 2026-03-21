<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Support\SecurityLogger;
use App\Models\Admin;
use App\Models\AdminOtp;
use App\Mail\AdminOtpMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Tag(
 *     name="Admin Auth",
 *     description="Admin authentication APIs"
 * )
 */

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

        if (Auth::guard('admin')->validate($request->only('email', 'password'))) {

            $admin = Admin::where('email', $request->email)->first();

            $otp = rand(100000, 999999);

            DB::table('admin_otps')->where('admin_id', $admin->id)->delete();

            DB::table('admin_otps')->insert([
                'admin_id' => $admin->id,
                'otp' => Hash::make($otp), 
                'expires_at' => now()->addMinutes(5),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Mail::to($admin->email)->send(new AdminOtpMail($otp));

            // FIXED SESSION KEY
            session(['admin_otp_id' => $admin->id]);

            SecurityLogger::log(
                'admin',
                $admin->id,
                'otp_sent',
                'Admin OTP sent'
            );

            return redirect()->route('admin.otp.verify.form');
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
        ]);
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
            ->latest()
            ->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'OTP not found.']);
        }

        if (now()->greaterThan($record->expires_at)) {
            return back()->withErrors(['otp' => 'OTP expired.']);
        }

        if (!Hash::check($request->otp, $record->otp)) {
            return back()->withErrors(['otp' => 'Invalid OTP.']);
        }

        $admin = Admin::findOrFail($adminId);

        Auth::guard('admin')->login($admin);

        session()->forget('admin_otp_id');

        $record->delete();

        SecurityLogger::log(
            'admin',
            $admin->id,
            'otp_login_success',
            'Admin logged in using OTP'
        );

        return redirect()->route('admin.dashboard');
    }

    /**
     * @OA\Post(
     *     path="/api/admin/send-mobile-otp",
     *     tags={"Admin Auth"},
     *     summary="Send OTP to mobile",
     *     operationId="sendMobileOtp",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"mobile"},
     *             @OA\Property(
     *                 property="mobile",
     *                 type="string",
     *                 description="Enter any registered mobile number"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP sent successfully"
     *     )
     * )
     */
    public function sendMobileOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10'
        ]);

        $admin = Admin::where('mobile', $request->mobile)
            ->where('status', 'active')
            ->first();

        if (!$admin) {
            return response()->json([
                'status' => false,
                'message' => 'Mobile not found'
            ], 404);
        }

        $otp = rand(100000, 999999);

        AdminOtp::where('admin_id', $admin->id)->delete();

        AdminOtp::create([
            'admin_id' => $admin->id,
            'otp' => bcrypt($otp),
            'expires_at' => now()->addMinutes(10),
        ]);

        $smsSent = SmsService::sendOtp($request->mobile, $otp);

        if (!$smsSent) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to send OTP'
            ], 500);
        }

        return response()->json([
            'status' => true,
            'message' => 'OTP sent successfully'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/admin/verify-mobile-otp",
     *     tags={"Admin Auth"},
     *     summary="Verify OTP and login",
     *     operationId="verifyMobileOtp",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"mobile","otp"},
     *             @OA\Property(
     *                 property="mobile",
     *                 type="string",
     *                 description="Enter mobile number"
     *             ),
     *             @OA\Property(
     *                 property="otp",
     *                 type="string",
     *                 description="Enter OTP received on mobile"
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Login success")
     * )
     */
    public function verifyMobileOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10',
            'otp' => 'required|digits:6'
        ]);

        $admin = Admin::where('mobile', $request->mobile)->first();

        if (!$admin) {
            return response()->json([
                'status' => false,
                'message' => 'Admin not found'
            ], 404);
        }

        $record = AdminOtp::where('admin_id', $admin->id)->latest()->first();

        if (!$record) {
            return response()->json([
                'status' => false,
                'message' => 'OTP not found'
            ], 400);
        }

        if (now()->gt($record->expires_at)) {
            return response()->json([
                'status' => false,
                'message' => 'OTP expired'
            ], 400);
        }

        if (!Hash::check($request->otp, $record->otp)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid OTP'
            ], 400);
        }

        $token = $admin->createToken('admin_token')->plainTextToken;

        $record->delete();

        return response()->json([
            'status' => true,
            'message' => 'Login success',
            'token' => $token,
            'admin' => $admin
        ]);
    }





}

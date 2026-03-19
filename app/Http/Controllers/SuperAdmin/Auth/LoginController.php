<?php

namespace App\Http\Controllers\SuperAdmin\Auth;

use App\Http\Controllers\Controller;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SuperAdmin;
use App\Models\SuperAdminOtp;
use App\Mail\SuperAdminOtpMail;
use Illuminate\Support\Facades\Hash;
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

        // Validate credentials without login
        if (Auth::guard('superadmin')->validate($credentials)) {

            $admin = SuperAdmin::where('email', $request->email)->first();

            $otp = rand(100000, 999999);

            // delete old OTP
            SuperAdminOtp::where('super_admin_id', $admin->id)->delete();

            // save OTP
            SuperAdminOtp::create([
                'super_admin_id' => $admin->id,
                'otp' => Hash::make($otp),
                'expires_at' => now()->addMinutes(5)
            ]);

            // send email
            Mail::to($admin->email)->send(new SuperAdminOtpMail($otp));

            // store session
            session(['superadmin_otp_id' => $admin->id]);

            SecurityLogger::log(
                'superadmin',
                $admin->id,
                'otp_sent',
                'Super admin OTP sent'
            );

            return redirect()->route('superadmin.otp.verify.form');
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
            return redirect()->route('superadmin.login');
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

        return redirect()->route('superadmin.dashboard');
    }

    /**
     * @OA\Post(
     *     path="/api/superadmin/send-mobile-otp",
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

        $admin = SuperAdmin::where('mobile', $request->mobile)
            ->where('is_active', '1')
            ->first();

        if (!$admin) {
            return response()->json([
                'is_active' => false,
                'message' => 'Mobile not found'
            ], 404);
        }

        $otp = rand(100000, 999999);

        SuperAdminOtp::where('super_admin_id', $admin->id)->delete();

        SuperAdminOtp::create([
            'super_admin_id' => $admin->id,
            'otp' => bcrypt($otp),
            'expires_at' => now()->addMinutes(10),
        ]);

        $smsSent = SmsService::sendOtp($request->mobile, $otp);

        if (!$smsSent) {
            return response()->json([
                'is_active' => false,
                'message' => 'Failed to send OTP'
            ], 500);
        }

        return response()->json([
            'is_active' => true,
            'message' => 'OTP sent successfully'
        ]);
    }

    //// DEBUG FUNCTION/////
    //   public function sendMobileOtp(Request $request)
    // {
    //     $mobile = $request->mobile;

    //         $otp = rand(100000, 999999);

    //         // Save OTP (important for verify)
    //     session([
    //         'otp' => $otp,
    //         'mobile' => $mobile
    //     ]);

    //         // 🔥 DEBUG ONLY (will stop execution and show OTP)
    //     \Log::info('OTP DEBUGg', [
    //     'mobile' => $mobile,
    //     'otp' => $otp
    // ]);


    // }
    /**
     * @OA\Post(
     *     path="/api/superadmin/verify-mobile-otp",
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

        $admin = SuperAdmin::where('mobile', $request->mobile)->first();

        if (!$admin) {
            return response()->json([
                'is_active' => false,
                'message' => 'Admin not found'
            ], 404);
        }

        $record = SuperAdmin::where('super_admin_id', $admin->id)->latest()->first();

        if (!$record) {
            return response()->json([
                'is_active' => false,
                'message' => 'OTP not found'
            ], 400);
        }

        if (now()->gt($record->expires_at)) {
            return response()->json([
                'is_active' => false,
                'message' => 'OTP expired'
            ], 400);
        }

        if (!\Hash::check($request->otp, $record->otp)) {
            return response()->json([
                'is_active' => false,
                'message' => 'Invalid OTP'
            ], 400);
        }

        $token = $admin->createToken('admin_token')->plainTextToken;

        $record->delete();

        return response()->json([
            'is_active' => true,
            'message' => 'Login success',
            'token' => $token,
            'admin' => $admin
        ]);
    }

    ///   DBUG FUNCTION ///
//      public function verifyMobileOtp(Request $request)
// {
//     $sessionOtp = session('otp');
//     $sessionMobile = session('mobile');

    //     \Log::info('VERIFY DEBUG', [
//         'entered_otp' => $request->otp,
//         'session_otp' => $sessionOtp,
//         'entered_mobile' => $request->mobile,
//         'session_mobile' => $sessionMobile
//     ]);

    //     if(!$sessionOtp){
//         return response()->json([
//             'status' => false,
//             'message' => 'Session expired'
//         ]);
//     }

    //     if($request->otp == $sessionOtp && $request->mobile == $sessionMobile){

    //         session()->forget(['otp','mobile']);

    //         return response()->json([
//             'status' => true,
//             'message' => 'OTP verified'
//         ]);
//     }

    //     return response()->json([
//         'status' => false,
//         'message' => 'Invalid OTP'
//     ]);
// }


}

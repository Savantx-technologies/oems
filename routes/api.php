<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;

Route::prefix('admin')->group(function () {
    Route::post('send-mobile-otp', [AdminLoginController::class, 'sendMobileOtp']);
    Route::post('verify-mobile-otp', [AdminLoginController::class, 'verifyMobileOtp']);
});

// test route
Route::get('/test', function () {
    return response()->json(['status' => 'API working']);
});
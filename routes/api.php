<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\SuperAdmin\Auth\LoginController as SuperAdminLoginController;

Route::prefix('admin')->name('admin.')->group(function () {

    Route::post('send-mobile-otp', [AdminLoginController::class, 'sendMobileOtp'])
        ->name('send.mobile-otp');

    Route::post('verify-mobile-otp', [AdminLoginController::class, 'verifyMobileOtp'])
        ->name('verify.mobile-otp');

});
Route::prefix('superadmin')->name('superadmin.')->group(function () {
    Route::post('send-mobile-otp', [SuperAdminLoginController::class, 'sendMobileOtp'])
        ->name('send.mobile-otp');

    Route::post('verify-mobile-otp', [SuperAdminLoginController::class, 'verifyMobileOtp'])
        ->name('verify.mobile-otp');
});

// test route
Route::get('/test', function () {
    return response()->json(['status' => 'API working']);
});
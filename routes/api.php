<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\transactionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

////////////////////////////////////////// Admin ////////////////////////////////

Route::group(['middleware' => ['auth:sanctum']], static fn(): array => [
    Route::post('/admin/index', [AdminController::class, 'index']),
    Route::post('admin/restore', [AdminController::class, 'restore']),
    Route::delete('admin/delete', [AdminController::class, 'destroy']),
    Route::delete('admin/force-delete', [AdminController::class, 'forceDelete']),
    Route::put('/admin/{id}/{column}', [AdminController::class, 'toggle']),
    Route::post('/admin/logout', [AdminController::class, 'logout']),
    Route::get('/admin/details', [AdminController::class, 'getCurrentAdmin']),
    Route::apiResource('admin', AdminController::class),
]);

Route::post('/admin/login', [AdminController::class, 'login']);

////////////////////////////////////////// Admin ////////////////////////////////


////////////////////////////////////////// driver ////////////////////////////////

Route::post('driver/login', [DriverController::class, 'login']);
Route::post('driver/register', [DriverController::class, 'register']);
Route::post('driver/verify-email', [DriverController::class, 'verifyEmail']);
Route::post('driver/check-verification-code', [DriverController::class, 'checkVerificationCode']);

////////////////////////////////////////// driver ////////////////////////////////


////////////////////////////////////////// users ////////////////////////////////
Route::prefix('user')->group(function () {
    Route::post('register', [UserController::class, 'register'])->middleware('throttle:5,1');
    Route::post('login', [UserController::class, 'login'])->middleware('throttle:5,1');
    Route::post('send-email-otp', [UserController::class, 'sendEmailOtp']);
    Route::post('verify-email-otp', [UserController::class, 'verifyEmailOtp']);
    Route::post('send-phone-otp', [UserController::class, 'sendPhoneOtp']);
    Route::post('verify-phone-otp', [UserController::class, 'verifyPhoneOtp']);
    Route::post('set-password', [UserController::class, 'setPassword']);

    Route::middleware(['auth:user'])->group(function () {
        Route::get('check-auth', [UserController::class, 'checkAuth']);
        Route::post('complete-profile', [UserController::class, 'completeProfile']);
        Route::post('logout', [UserController::class, 'logout']);
    });
});
////////////////////////////////////////// users ////////////////////////////////
<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Models\District;
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
Route::prefix('driver')->middleware('appthrottle:7')->group(function () {
    Route::post('login', [DriverController::class, 'login']);
    Route::post('register', [DriverController::class, 'register']);
    Route::post('verify-email', [DriverController::class, 'verifyEmail']);
    Route::post('check-verification-code', [DriverController::class, 'checkVerificationCode']);
    Route::post('set-password', [DriverController::class, 'setPassword']);
    Route::post('forgot-password', [DriverController::class, 'forgotPassword']);
    Route::post('forgot-verify-otp', [DriverController::class, 'verifyEmailOrPhoneOtp']);
    Route::post('set-new-password', [DriverController::class, 'setNewPassword']);


    Route::middleware(['auth:driver'])->group(function () {
        Route::post('upload-license-front', [DriverController::class, 'uploadLicenseFront']);
        Route::post('upload-license-back', [DriverController::class, 'uploadLicenseBack']);
        Route::post('upload-criminal-record', [DriverController::class, 'uploadCriminalRecord']);
        Route::post('upload-car-details', [DriverController::class, 'uploadCarDetails']);
        Route::get('check-auth', [DriverController::class, 'checkAuth']);
        Route::post('complete-profile', [DriverController::class, 'completeProfile']);
        Route::post('logout', [DriverController::class, 'logout']);
        Route::post('update-profile', [DriverController::class, 'updateProfile']);
        Route::post('orders/{order}/request', [OrderController::class, 'createOrderDriver']);
    });
});
////////////////////////////////////////// driver ////////////////////////////////


////////////////////////////////////////// users ////////////////////////////////
Route::prefix('user')->middleware('appthrottle:15')->group(function () {
    Route::post('register', [UserController::class, 'register']);
    Route::post('login', [UserController::class, 'login']);
    Route::post('send-email-otp', [UserController::class, 'sendEmailOtp']);
    Route::post('verify-email-otp', [UserController::class, 'verifyEmailOtp']);
    Route::post('send-phone-otp', [UserController::class, 'sendPhoneOtp']);
    Route::post('verify-phone-otp', [UserController::class, 'verifyPhoneOtp']);
    Route::post('set-password', [UserController::class, 'setPassword']);
    // Forget password
    Route::post('forgot-password', [UserController::class, 'forgotPassword']);
    Route::post('forgot-verify-otp', [UserController::class, 'verifyEmailOrPhoneOtp']);
    Route::post('set-new-password', [UserController::class, 'setNewPassword']);

    Route::middleware(['auth:user'])->group(function () {
        Route::get('check-auth', [UserController::class, 'checkAuth']);
        Route::post('complete-profile', [UserController::class, 'completeProfile']);
        Route::post('logout', [UserController::class, 'logout']);

        ////? Orders
        // Route::post('expected-price', [AreaController::class, 'calculatePrice']);
        Route::get('order-details/{order_id}', [OrderController::class, 'show']);
        Route::get('price-per-km', [CountryController::class, 'getPricePerKmByIp']);
        Route::post('create-order', [OrderController::class, 'createOrder']);
        Route::post('accept-order-request', [OrderController::class, 'acceptOrderRequest']);
        Route::post('cancel-order', [OrderController::class, 'cancelOrder']);
    });
});
////////////////////////////////////////// users ////////////////////////////////


////////////////////////////////////////// general ////////////////////////////////
Route::middleware('appthrottle:15')->group(function () {
    Route::get('cities', [CityController::class, 'index']);
    Route::get('districts', [DistrictController::class, 'index']);
});
////////////////////////////////////////// general ////////////////////////////////

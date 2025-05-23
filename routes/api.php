<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketDriverController;
use App\Http\Controllers\UserController;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

////////////////////////////////////////// Admin ////////////////////////////////

Route::prefix('admin')->middleware('appthrottle:7')->group(function () {
    Route::group(['middleware' => ['auth:admin']], static fn(): array => [
        Route::post('index', [AdminController::class, 'index']),
        Route::post('restore', [AdminController::class, 'restore']),
        Route::delete('delete', [AdminController::class, 'destroy']),
        Route::delete('force-delete', [AdminController::class, 'forceDelete']),
        Route::put('{id}/{column}', [AdminController::class, 'toggle']),
        Route::post('logout', [AdminController::class, 'logout']),
        Route::get('details', [AdminController::class, 'getCurrentAdmin']),
        Route::apiResource('admin', AdminController::class),

        ////? Notifications
        Route::post('send-notification', [NotificationController::class, 'store']),
    ]);

    Route::post('login', [AdminController::class, 'login']);
});

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
        Route::post('/contact-us', [ContactUsController::class, 'store']);

        Route::post('change-password', [DriverController::class, 'changePassword']);
        //upload
        Route::post('upload-license-front', [DriverController::class, 'uploadLicenseFront']);
        Route::post('upload-license-back', [DriverController::class, 'uploadLicenseBack']);
        Route::post('upload-criminal-record', [DriverController::class, 'uploadCriminalRecord']);
        Route::post('upload-car-details', [DriverController::class, 'uploadCarDetails']);

        //auth
        Route::get('check-auth', [DriverController::class, 'checkAuth']);

        //complete profile
        Route::post('complete-profile', [DriverController::class, 'completeProfile']);

        //    logout
        Route::post('logout', [DriverController::class, 'logout']);

        //    update-profile
        Route::post('update-profile', [DriverController::class, 'updateProfile']);

        //    request Order
        Route::post('orders/{order}/request', [OrderController::class, 'createOrderDriver']);

        //    massage
        Route::post('send-ticket', [TicketDriverController::class, 'store']);

        //    history
        Route::get('orders/history', [OrderController::class, 'historyDriverOrders']);

        Route::delete('delete-account', [DriverController::class, 'deleteAccount']);

        Route::post('orders/{id}/in-transit', [OrderController::class, 'startDelivery']);

        Route::post('orders/{id}/completed', [OrderController::class, 'finishDelivery']);

        ////? Notifications
        Route::get('my-notifications', [NotificationController::class, 'getMyNotifications']);
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
        Route::post('check-auth', [UserController::class, 'checkAuth']);
        Route::post('complete-profile', [UserController::class, 'completeProfile']);
        Route::post('logout', [UserController::class, 'logout']);
        Route::post('update-profile', [UserController::class, 'updateProfile']);
        Route::post('change-password', [UserController::class, 'changePassword']);
        Route::post('delete-account', [UserController::class, 'deleteAccount']);
        ////? Addresses
        Route::get('my-addresses', [AddressController::class, 'myAddresses']);
        Route::post('create-new-address', [AddressController::class, 'store']);
        Route::post('update-address/{id}', [AddressController::class, 'update']);
        Route::post('delete-address/{id}', [AddressController::class, 'destroy']);

        ////? Orders
        // Route::post('expected-price', [AreaController::class, 'calculatePrice']);
        Route::get('order-details/{order_id}', [OrderController::class, 'show']);
        Route::get('price-per-km', [CountryController::class, 'getPricePerKmByIp']);
        Route::get('history-orders', [OrderController::class, 'historyOrders']);
        Route::post('create-order', [OrderController::class, 'createOrder']);
        Route::post('accept-order-request', [OrderController::class, 'acceptOrderRequest']);
        Route::post('cancel-order', [OrderController::class, 'cancelOrder']);

        ////? Tickets
        Route::post('send-ticket', [TicketController::class, 'store']);

        ////? Notifications
        Route::get('my-notifications', [NotificationController::class, 'getMyNotifications']);
    });
});
////////////////////////////////////////// users ////////////////////////////////


////////////////////////////////////////// general ////////////////////////////////
Route::middleware('appthrottle:15')->group(function () {
    Route::get('cities', [CityController::class, 'index']);
    Route::get('districts', [DistrictController::class, 'index']);
    Route::get('/pages/{slug}', [PageController::class, 'show']);
});
////////////////////////////////////////// general ////////////////////////////////

<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/realtime-user', function () {
    return view('test-order-realtime-user');
});
Route::get('/realtime-driver', function () {
    return view('test-order-realtime-driver');
});

// Route::post('/broadcasting/auth', function () {
//     return Broadcast::auth(request());
// });
<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/realtime', function () {
    return view('test-order-realtime');
});

// Route::post('/broadcasting/auth', function () {
//     return Broadcast::auth(request());
// });

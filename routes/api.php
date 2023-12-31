<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;



Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login')->middleware('force-logged-out');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
});


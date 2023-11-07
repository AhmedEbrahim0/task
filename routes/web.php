<?php

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\UsersController;



Route::group(['middleware' => ['check_limits'] ], function () {
    Route::get('/', [AuthController::class ,'loginPage']);
    Route::get('/login', [AuthController::class ,'loginPage'])->name("login");
});
Route::post('/login', [AuthController::class ,'login'])->middleware('limit_request');



Route::group(['middleware' => ['auth','check_limits'] ], function () {
    
    Route::post('/logout',[AuthController::class,'logout']);
    Route::get('/', [UsersController::class,'index']);
    Route::post('/add-new-user', [UsersController::class,'store']);
    Route::post('/delete-user', [UsersController::class,'destroy']);

    
});
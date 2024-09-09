<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('users', UserController::class)->middleware(['auth:api', 'Admin']);

// ***************  Auth Routes *********************************

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/profile', [AuthController::class, 'profile']);
});

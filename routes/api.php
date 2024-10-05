<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('users', UserController::class)->middleware(['auth:api', 'Admin']);
Route::delete('users/{id}/force-delete', [UserController::class, 'forceDeleteUser']);
Route::post('users/{id}/restore', [UserController::class, 'restoreUser']);

// ***************  Auth Routes *********************************

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/profile', [AuthController::class, 'profile']);
});

// ************* Tasks Routes **********************************
// Route::middleware('auth:api')->group(function () {
    Route::get('tasks', [TaskController::class, 'index']);
    Route::get('tasks/{id}', [TaskController::class, 'show']);
    Route::post('tasks', [TaskController::class, 'store']);
    Route::post('tasks/{id}/assign', [TaskController::class, 'assignTask']);
    Route::put('tasks/{id}', [TaskController::class, 'updateStatus']);
    Route::delete('tasks/{id}', [TaskController::class, 'destroy']);
    Route::delete('tasks/{id}/force-delete', [TaskController::class, 'forceDeleteTask']);
    Route::post('tasks/{id}/restore', [TaskController::class, 'restoreTask']);

// });

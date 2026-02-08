<?php

use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::get('/users/me', [UserController::class, 'me']);
    Route::post('/users/me/change-password', [UserController::class, 'changeOwnPassword']);
    
    Route::patch('/users/{user}/activate', [UserController::class, 'activate']);
    Route::patch('/users/{user}/deactivate', [UserController::class, 'deactivate']);
    Route::post('/users/{user}/change-password', [UserController::class, 'changePassword']);
    
    Route::apiResource('users', UserController::class);
});

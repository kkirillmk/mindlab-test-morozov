<?php

use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api', 'active'])->group(function () {
    Route::get('/users/me', [UserController::class, 'me']);
    Route::post('/users/me/change-password', [UserController::class, 'changeOwnPassword']);
});

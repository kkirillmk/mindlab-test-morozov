<?php

use App\Http\Controllers\Api\V1\HealthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/health', HealthController::class);

    require __DIR__.'/api/v1/auth.php';
    require __DIR__.'/api/v1/users.php';

    Route::prefix('admin')->group(function () {
        require __DIR__.'/api/v1/admin/users.php';
        require __DIR__.'/api/v1/admin/roles.php';
    });
});

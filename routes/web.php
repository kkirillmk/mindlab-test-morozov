<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'name' => 'MindLab API',
        'version' => '1.0.0',
        'documentation' => 'todo',
    ]);
});

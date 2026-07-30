<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\ApiKeyMiddleware;

// Rutas protegidas con API Key
Route::middleware(ApiKeyMiddleware::class)->group(function () {
    Route::apiResource('users', UserController::class);
});
<?php

use App\Infrastructure\Http\Controllers\AuthController;
use App\Infrastructure\Http\Middleware\JwtAuthMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::middleware(JwtAuthMiddleware::class)->group(function () {
            Route::get('me', [AuthController::class, 'me']);
            Route::post('logout', [AuthController::class, 'logout']);
        });

        Route::post('login', [AuthController::class, 'login']);
        Route::get('{provider}', [AuthController::class, 'redirectToProvider']);
        Route::get('{provider}/callback', [AuthController::class, 'handleProviderCallback']);
    });
});

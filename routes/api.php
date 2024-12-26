<?php

use App\Auth\Infrastructure\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::middleware('auth')->group(function () {
            Route::get('me', [AuthController::class, 'me']);
            Route::post('logout', [AuthController::class, 'logout']);
        });

        Route::post('login', [AuthController::class, 'login']);
        Route::get('{provider}', [AuthController::class, 'redirectToProvider']);
        Route::get('{provider}/callback', [AuthController::class, 'handleProviderCallback']);
    });
});

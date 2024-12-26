<?php

use App\Auth\Infrastructure\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/login', function () {
    return Inertia::render('Auth/Login');
});

Route::get('/register', function () {
    return Inertia::render('Auth/Register');
});

Route::prefix('auth')->group(function () {
    Route::get('{provider}', [AuthController::class, 'redirectToProvider']);
    Route::get('{provider}/callback', [AuthController::class, 'handleProviderCallback']);
});

Route::middleware('web.auth')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    });
});

<?php

use App\Auth\Infrastructure\Http\Controllers\AuthController;
use App\Group\Infrastructure\Http\Controllers\GroupController;
use App\Section\Infrastructure\Http\Controllers\SectionController;
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

    Route::get('profile', function () {
        return Inertia::render('Profile');
    });

    Route::prefix('group')->group(function () {
        Route::get('/{id}/member', [GroupController::class, 'showGroupMembers']);
    });

    Route::prefix('section')->group(function () {
        Route::prefix('{id}/lesson')->group(function () {
            Route::get('{lessonId}/edit', [SectionController::class, 'showLessonEditorPage']);
        });
    });
});

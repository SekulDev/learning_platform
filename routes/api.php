<?php

use App\Auth\Infrastructure\Http\Controllers\AuthController;
use App\Group\Infrastructure\Http\Controllers\GroupController;
use App\Media\Infrastructure\Http\Controllers\MediaController;
use App\Notification\Infrastructure\Http\Controllers\NotificationController;
use App\Section\Infrastructure\Http\Controllers\SectionController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::middleware('api.auth')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);

        Route::put('me', [AuthController::class, 'update']);
    });

    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::middleware('api.auth')->group(function () {

    Route::prefix('group')->group(function () {
        Route::get('/', [GroupController::class, 'getGroups']);
        Route::post('/', [GroupController::class, 'createGroup']);
        Route::delete('/{id}', [GroupController::class, 'deleteGroup']);

        Route::get('/{id}/member', [GroupController::class, 'getMembers']);
        Route::post('/{id}/member', [GroupController::class, 'addMemberToGroup']);
        Route::delete('/{id}/member/{userId}', [GroupController::class, 'removeMemberFromGroup']);

        Route::get('/owner', [GroupController::class, 'getOwnedGroups']);
    });

    Route::prefix('notification')->group(function () {
        Route::get('/', [NotificationController::class, 'getNotifications']);
        Route::get('/count', [NotificationController::class, 'getUnreadedCount']);

        Route::post('/{id}/read', [NotificationController::class, 'readNotification']);
        Route::post('/read-all', [NotificationController::class, 'readAllNotifications']);
    });

    Route::prefix('section')->group(function () {
        Route::post('/', [SectionController::class, 'createSection']);
        Route::delete('/{id}', [SectionController::class, 'removeSection']);


        Route::prefix('{id}/lesson')->group(function () {
            Route::post('/', [SectionController::class, 'createLesson']);
            Route::delete('/{lessonId}', [SectionController::class, 'removeLesson']);
        });

        Route::get('/owner', [SectionController::class, 'getOwnedSections']);
    });

    Route::prefix('media')->group(function () {
        Route::post('/', [MediaController::class, 'uploadMedia']);
    });

});

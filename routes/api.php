<?php

use App\Auth\Infrastructure\Http\Controllers\AuthController;
use App\Group\Infrastructure\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::middleware('api.auth')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });

    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});


Route::middleware('api.auth')->group(function () {

    Route::prefix('group')->group(function () {
        Route::post('/', [GroupController::class, 'createGroup']);
        Route::delete('/{id}', [GroupController::class, 'deleteGroup']);

        Route::get('/{id}/member', [GroupController::class, 'getMembers']);
        Route::post('/{id}/member', [GroupController::class, 'addMemberToGroup']);
        Route::delete('/{id}/member/{$userId}', [GroupController::class, 'removeMemberFromGroup']);
    });

});

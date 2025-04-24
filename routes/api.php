<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::prefix('auth')->group(function () {
    Route::get('/sanctum', function (Request $request) {
        return $request->bearerToken();
    })->middleware('auth:sanctum');

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/invite-user', [AuthController::class, 'inviteUser'])->middleware('auth:sanctum');
    Route::post('/activate', [AuthController::class, 'activateUser'])->name('activate-user');
});

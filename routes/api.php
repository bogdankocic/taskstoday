<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrganizationsController;

Route::prefix('auth')->group(function () {
    Route::get('/sanctum', function (Request $request) {
        return $request->bearerToken();
    })->middleware('auth:sanctum');

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/invite-user', [AuthController::class, 'inviteUser'])->middleware('auth:sanctum');
    Route::post('/activate', [AuthController::class, 'activateUser'])->name('activate-user');
});

Route::prefix('organizations')->group(function () {
    Route::get('/', [OrganizationsController::class, 'get'])->middleware('auth:sanctum');
    Route::post('/', [OrganizationsController::class, 'create'])->middleware('auth:sanctum');
    Route::get('/{id}', [OrganizationsController::class, 'getOne'])->middleware('auth:sanctum');
    Route::post('/{id}', [OrganizationsController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{id}', [OrganizationsController::class, 'delete'])->middleware('auth:sanctum');
});

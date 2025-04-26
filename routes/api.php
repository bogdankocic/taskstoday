<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrganizationsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TaskController;

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

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'get'])->middleware('auth:sanctum');
    Route::post('/self-update', [UserController::class, 'selfUpdate'])->middleware('auth:sanctum');
    Route::delete('/{id}', [UserController::class, 'delete'])->middleware('auth:sanctum');
});

Route::prefix('projects')->group(function () {
    Route::get('/', [ProjectController::class, 'get'])->middleware('auth:sanctum');
    Route::post('/', [ProjectController::class, 'create'])->middleware('auth:sanctum');
    Route::post('/{id}', [ProjectController::class, 'update'])->middleware('auth:sanctum');
    Route::post('/{id}/finish', [ProjectController::class, 'finish'])->middleware('auth:sanctum');
    Route::delete('/{id}', [ProjectController::class, 'delete'])->middleware('auth:sanctum');
});

Route::prefix('teams')->group(function () {
    Route::post('/', [TeamController::class, 'create'])->middleware('auth:sanctum');
    Route::post('/{id}', [TeamController::class, 'updateName'])->middleware('auth:sanctum');
    Route::delete('/{id}', [TeamController::class, 'delete'])->middleware('auth:sanctum');
    Route::post('/{teamId}/members/{userId}', [TeamController::class, 'addMember'])->middleware('auth:sanctum');
    Route::delete('/{teamId}/members/{userId}', [TeamController::class, 'removeMember'])->middleware('auth:sanctum');
});

Route::prefix('tasks')->group(function () {
    Route::get('/', [TaskController::class, 'get'])->middleware('auth:sanctum');
    Route::post('/', [TaskController::class, 'create'])->middleware('auth:sanctum');
    Route::post('/{task}', [TaskController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{task}', [TaskController::class, 'delete'])->middleware('auth:sanctum');
    Route::post('/{task}/activate', [TaskController::class, 'activate'])->middleware('auth:sanctum');
    Route::post('/{task}/complete', [TaskController::class, 'complete'])->middleware('auth:sanctum');
    Route::post('/{task}/vote', [TaskController::class, 'complexityVote'])->middleware('auth:sanctum');
    Route::post('/{task}/assign-performer/{user}', [TaskController::class, 'assignPerformer'])->middleware('auth:sanctum');
    Route::post('/{task}/assign-contributor/{user}', [TaskController::class, 'assignContributor'])->middleware('auth:sanctum');
});

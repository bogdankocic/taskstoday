<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\OrganizationsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProjectChatMessageController;
use App\Http\Controllers\TaskNoteController;

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
    Route::get('/{organization}', [OrganizationsController::class, 'getOne'])->middleware('auth:sanctum');
    Route::post('/{organization}', [OrganizationsController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{organization}', [OrganizationsController::class, 'delete'])->middleware('auth:sanctum');
});

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'get'])->middleware('auth:sanctum');
    Route::get('/self', [UserController::class, 'self'])->middleware('auth:sanctum');
    Route::post('/self-update', [UserController::class, 'selfUpdate'])->middleware('auth:sanctum');
    Route::delete('/{user}', [UserController::class, 'delete'])->middleware('auth:sanctum');
});

Route::prefix('projects')->group(function () {
    Route::get('/', [ProjectController::class, 'get'])->middleware('auth:sanctum');
    Route::post('/', [ProjectController::class, 'create'])->middleware('auth:sanctum');
    Route::post('/{project}', [ProjectController::class, 'update'])->middleware('auth:sanctum');
    Route::get('/{project}/members', [ProjectController::class, 'getMembers'])->middleware('auth:sanctum');
    Route::get('/{project}/teams', [ProjectController::class, 'getTeams'])->middleware('auth:sanctum');
    Route::post('/{project}/finish', [ProjectController::class, 'finish'])->middleware('auth:sanctum');
    Route::delete('/{project}', [ProjectController::class, 'delete'])->middleware('auth:sanctum');
});

Route::prefix('teams')->group(function () {
    Route::post('/', [TeamController::class, 'create'])->middleware('auth:sanctum');
    Route::get('/{team}', [TeamController::class, 'getOne'])->middleware('auth:sanctum');
    Route::post('/{team}', [TeamController::class, 'updateName'])->middleware('auth:sanctum');
    Route::delete('/{team}', [TeamController::class, 'delete'])->middleware('auth:sanctum');
    Route::get('/{team}/members', [TeamController::class, 'getMembers'])->middleware('auth:sanctum');
    Route::post('/{team}/members/{user}', [TeamController::class, 'addMember'])->middleware('auth:sanctum');
    Route::delete('/{team}/members/{user}', [TeamController::class, 'removeMember'])->middleware('auth:sanctum');
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

Route::prefix('notifications')->group(function () {
    Route::get('/', [NotificationController::class, 'get'])->middleware('auth:sanctum');
    Route::post('/seen', [NotificationController::class, 'markAsSeen'])->middleware('auth:sanctum');
});

Route::prefix('files')->group(function () {
    Route::post('/', [FileController::class, 'upload'])->middleware('auth:sanctum');
    Route::get('/projects/{project}', [FileController::class, 'getByProject'])->middleware('auth:sanctum');
    Route::get('/tasks/{task}', [FileController::class, 'getByTask'])->middleware('auth:sanctum');
    Route::delete('/', [FileController::class, 'delete'])->middleware('auth:sanctum');
    Route::get('/{projectFile}/projects/download', [FileController::class, 'downloadProjectFile'])->middleware('auth:sanctum');
    Route::get('/{taskFile}/tasks/download', [FileController::class, 'downloadTaskFile'])->middleware('auth:sanctum');
});

Route::prefix('task-notes')->group(function () {
    Route::post('/tasks/{task}', [TaskNoteController::class, 'create'])->middleware('auth:sanctum');
    Route::get('/tasks/{task}', [TaskNoteController::class, 'get'])->middleware('auth:sanctum');
});

Route::prefix('chat-messages')->group(function () {
    Route::post('/projects/{project}', [ProjectChatMessageController::class, 'create'])->middleware('auth:sanctum');
    Route::get('/projects/{project}', [ProjectChatMessageController::class, 'get'])->middleware('auth:sanctum');
});

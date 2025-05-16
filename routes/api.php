<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskStatusController;
use App\Http\Controllers\TeamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
    Route::apiResource('tasks', TaskController::class);
    Route::apiResource('teams', TeamController::class);
    Route::apiResource('task-statuses', TaskStatusController::class);


    Route::post('/teams/{team}/members', [TeamController::class, 'addMembers']);
    Route::delete('/teams/{team}/members/{user}', [TeamController::class, 'removeMember']);
    Route::post('/teams/{team}/leaders/{user}', [TeamController::class, 'assignLeader']);
    Route::delete('/teams/{team}/leaders/{user}', [TeamController::class, 'removeLeader']);
    Route::post('/teams/{team}/tasks/{task}', [TeamController::class, 'assignTask']);
    Route::delete('/teams/{team}/tasks/{task}', [TeamController::class, 'removeTask']);
});
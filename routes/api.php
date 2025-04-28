<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\WorkTargetController;
use App\Http\Controllers\NotificationController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // User routes
    Route::get('/user-types', [UserController::class, 'getUserTypes']);
    Route::get('/pyd-groups', [UserController::class, 'getPydGroups']);
    Route::get('/ppp-users', [UserController::class, 'getPPPUsers']);
    Route::get('/ppk-users', [UserController::class, 'getPPKUsers']);
    Route::get('/pyd-users', [UserController::class, 'getPYDUsers']);
    Route::apiResource('users', UserController::class);

    // Evaluation routes
    Route::apiResource('evaluations', EvaluationController::class);
    Route::post('/evaluations/{evaluation}/submit/{sectionCode}', [EvaluationController::class, 'submitSection']);
    Route::post('/evaluations/{evaluation}/marks', [EvaluationController::class, 'saveMarks']);

    // Work target routes
    Route::apiResource('evaluations.work-targets', WorkTargetController::class)
        ->shallow()
        ->except(['index', 'show']);
    Route::get('/evaluations/{evaluation}/work-targets', [WorkTargetController::class, 'index']);
    Route::get('/work-targets/{workTarget}', [WorkTargetController::class, 'show']);

    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::put('/notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
    Route::put('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy']);
});
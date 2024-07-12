<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware('auth:api')->prefix('v1')->group(function () {
    Route::get('user', [AuthController::class, 'show']);

    Route::apiResource('quizzes', QuizController::class);
    Route::apiResource('questions', QuestionController::class);

    Route::get('profile', [ProfileController::class, 'show']);
});

Route::middleware(['auth:api', RoleMiddleware::class])->group(function () {
    Route::apiResource('profile', ProfileController::class);
});

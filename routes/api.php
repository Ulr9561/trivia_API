<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizController;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware('auth:api')->prefix('v1')->group(function () {
    Route::get('user', [AuthController::class, 'show']);

    Route::apiResource('quizzes', QuizController::class);
    Route::apiResource('questions', QuestionController::class);
});

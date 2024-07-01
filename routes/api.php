<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\CustomTokenAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware(CustomTokenAuth::class)->group(function () {
    Route::get('user', function (Request $request) {
        return [
            'success' => 'Bien joué vieux !',
        ];
    });
});

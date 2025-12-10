<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::apiResource('orders', OrderController::class);
        Route::get('/orders/{order}/payments', [PaymentController::class, 'show']);
        Route::post('/orders/{order}/payments', [PaymentController::class, 'process']);
        Route::get('/payments', [PaymentController::class, 'index']);
    });
});


<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;




Route::prefix('auth')->group(function (): void {

    Route::post('/register', [AuthController::class, 'store'])->middleware('throttle:10,30');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:20,30');

    Route::middleware('auth:sanctum')->group(function (): void {

        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'show']);
        Route::put('/user', [AuthController::class, 'update']);
        Route::delete('/user', [AuthController::class, 'destroy']);
        Route::get('/users', [AuthController::class, 'index']);
        Route::post('/verification', [AuthController::class, 'verification'])->middleware('throttle:10,30');

    });

});




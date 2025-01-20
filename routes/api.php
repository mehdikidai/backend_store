<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::prefix('products')->group(function (): void {

    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{id}', [ProductController::class, 'show']);
    Route::middleware('auth:sanctum')->group(function (): void {
        Route::delete('/{id}', [ProductController::class, 'destroy']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::post('/', [ProductController::class, 'store']);
    });

});

Route::prefix('auth')->group(function (): void {

    Route::post('/register', [AuthController::class, 'store']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function (): void {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'show']);
    });

});


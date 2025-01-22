<?php

use App\Enum\Roles;
use App\Models\Role;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategoryController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


// start section for products routes

Route::prefix('products')->group(function (): void {

    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{id}', [ProductController::class, 'show']);
    Route::middleware('auth:sanctum')->group(function (): void {
        Route::delete('/{id}', [ProductController::class, 'destroy']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::post('/', [ProductController::class, 'store']);
    });

});

// end section for products routes


// start section for auth routes

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

// end section for auth routes



// Start section for categories routes

Route::middleware(['auth:sanctum', 'isAdmin'])->group(function (): void {

    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);

});

Route::prefix('categories')->group(function () {

    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{id}', [CategoryController::class, 'show']);

});


// End section for categories routes


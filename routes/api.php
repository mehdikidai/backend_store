<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\SizeController;



// start section for products routes

Route::prefix('products')->group(function (): void {

    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{id}', [ProductController::class, 'show']);

    Route::middleware(['auth:sanctum', 'role:admin'])->group(function (): void {

        Route::delete('/{id}', [ProductController::class, 'destroy']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::post('/', [ProductController::class, 'store']);

    });

});

// end section for products routes


// Start section for categories routes

Route::middleware(['auth:sanctum', 'role:admin'])->group(function (): void {

    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);

});

Route::prefix('categories')->group(function (): void {

    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{id}', [CategoryController::class, 'show']);

});


// End section for categories routes


// Start section for colors routes

Route::middleware(['auth:sanctum', 'role:admin,editor'])->group(function (): void {

    Route::apiResource('colors', ColorController::class)->except(['index', 'show']);

});

Route::prefix('colors')->group(function (): void {

    Route::get('/', [ColorController::class, 'index']);
    Route::get('/{id}', [ColorController::class, 'show']);

});

// End section for colors routes


// Start section for size routes

Route::middleware(['auth:sanctum', 'role:admin'])->group(function (): void {

    Route::apiResource('sizes', SizeController::class)->except(['index', 'show']);

});

Route::prefix('sizes')->group(function (): void {

    Route::get('/', [SizeController::class, 'index']);
    Route::get('/{id}', [SizeController::class, 'show']);

});

// End section for size routes






// start section for auth routes

require_once __DIR__ . '/authApi.php';

// end section for auth routes

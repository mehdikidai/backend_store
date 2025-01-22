<?php

use App\Enum\Roles;
use App\Models\Role;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\AuthController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');



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

    Route::post('/register', [AuthController::class, 'store'])->middleware('throttle:10,30');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:20,30');

    Route::middleware('auth:sanctum')->group(function (): void {

        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'show']);
        Route::get('/users',[AuthController::class,'index']);
        Route::post('/verification',[AuthController::class,'verification'])->middleware('throttle:10,30');

    });

});



Route::get('/test',function(){
    $roleId = Role::where('name', Roles::Customer->value)->pluck('id')->first();
    return response()->json(['roleId'=>$roleId]);
});

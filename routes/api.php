<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductAttributeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

// 🟢 Публичные маршруты (просмотр)
Route::apiResource('products', ProductController::class)->only(['index', 'show']);
Route::apiResource('tags', TagController::class)->only(['index', 'show']);
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);

// 🔒 Защищённые маршруты (нужна авторизация)
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('products', ProductController::class)->except(['index', 'show']);
    Route::apiResource('tags', TagController::class)->except(['index', 'show']);
    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);

    Route::post('images', [ProductImageController::class, 'store']);
    Route::delete('images/{image}', [ProductImageController::class, 'destroy']);

    Route::post('attributes', [ProductAttributeController::class, 'store']);
    Route::put('attributes/{attribute}', [ProductAttributeController::class, 'update']);
    Route::delete('attributes/{attribute}', [ProductAttributeController::class, 'destroy']);
});

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $user = Auth::user();
    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json(['token' => $token]);
});


<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Publik
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Harus login (user & admin): Create, Read, Update
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // === Route CRU tiap anggota taruh di sini ===
    // Route::apiResource('categories', CategoryController::class)->except(['destroy']);
});

// Hanya admin: Delete
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    // === Route DELETE tiap anggota taruh di sini ===
    // Route::delete('categories/{category}', [CategoryController::class, 'destroy']);
});
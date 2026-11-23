<?php
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

// Terbuka
Route::get('/ping', fn () => ['message' => 'API Laravel berjalan']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Harus login (user & admin): Create, Read, Update
Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // ===== Categories (anggota 1) =====
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show'])->whereNumber('id');
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::match(['put', 'patch'], '/categories/{id}', [CategoryController::class, 'update'])->whereNumber('id');

    // ===== Products (anggota 2): tempel blok di sini =====
    // ===== Transactions (anggota 3): tempel blok di sini =====
Route::get('/transactions', [TransactionController::class, 'index']);

Route::get('/transactions/{id}', [TransactionController::class, 'show'])
    ->whereNumber('id');
Route::post('/transactions', [TransactionController::class, 'store']);
Route::match(['put', 'patch'], '/transactions/{id}', [TransactionController::class, 'update'])
    ->whereNumber('id');

    // ===== Product Reviews (anggota 4): tempel blok di sini =====

    // Hanya admin: Delete (berada di dalam auth:api, jadi 401 muncul lebih dulu)
    Route::middleware('role:admin')->group(function () {
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->whereNumber('id');
        // Route::delete products  
        // Route::delete transactions 
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy'])
        ->whereNumber('id');
        // Route::delete product_reviews di sini
    });
});
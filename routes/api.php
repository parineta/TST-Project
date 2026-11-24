
<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// ===== ROUTE PUBLIK =====
Route::get('/ping', fn () => [
    'message' => 'API Laravel berjalan'
]);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ===== ROUTE USER & ADMIN (JWT) =====
Route::middleware('auth:api')->group(function () {

    // Authentication
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // ===== CATEGORIES (PARI) =====
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show'])
        ->whereNumber('id');

    Route::post('/categories', [CategoryController::class, 'store']);

    Route::match(['put', 'patch'], '/categories/{id}', [
        CategoryController::class, 'update'
    ])->whereNumber('id');


    // ===== PRODUCTS (ZAI) =====
    // Route Products ditambahkan setelah fitur selesai.


    // ===== TRANSACTIONS (LELY) =====
    Route::get('/transactions', [TransactionController::class, 'index']);

    Route::get('/transactions/{id}', [TransactionController::class, 'show'])
        ->whereNumber('id');

    Route::post('/transactions', [TransactionController::class, 'store']);

    Route::match(['put', 'patch'], '/transactions/{id}', [
        TransactionController::class, 'update'
    ])->whereNumber('id');


    // ===== PRODUCT REVIEWS (KAMU) =====

    // GET semua review
    Route::get('/product-reviews', [
        ProductReviewController::class, 'index'
    ]);

    // GET detail review
    Route::get('/product-reviews/{id}', [
        ProductReviewController::class, 'show'
    ])->whereNumber('id');

    // POST tambah review
    Route::post('/product-reviews', [
        ProductReviewController::class, 'store'
    ]);

    // PUT/PATCH update review
    Route::match(['put', 'patch'], '/product-reviews/{id}', [
        ProductReviewController::class, 'update'
    ])->whereNumber('id');


    // ===== KHUSUS ADMIN (DELETE) =====
    Route::middleware('role:admin')->group(function () {

        // Delete Categories
        Route::delete('/categories/{id}', [
            CategoryController::class, 'destroy'
        ])->whereNumber('id');

        // Delete Products
        // Ditambahkan setelah fitur Products selesai.

        // Delete Transactions
        Route::delete('/transactions/{id}', [
            TransactionController::class, 'destroy'
        ])->whereNumber('id');

        // Delete Product Reviews
        Route::delete('/product-reviews/{id}', [
            ProductReviewController::class, 'destroy'
        ])->whereNumber('id');

    });
});

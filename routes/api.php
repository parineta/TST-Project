<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES - Tanpa Login
|--------------------------------------------------------------------------
*/

Route::get('/ping', fn () => [
    'message' => 'API Laravel berjalan'
]);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Products - Public
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show'])
    ->whereNumber('id');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES - JWT (USER & ADMIN)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api')->group(function () {

    // ===== Auth =====
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // ===== Categories (Pari) =====
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show'])
        ->whereNumber('id');
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::match(['put', 'patch'], '/categories/{id}', [
        CategoryController::class, 'update'
    ])->whereNumber('id');

    // ===== Products (Zai) =====
    Route::post('/products', [ProductController::class, 'store']);
    Route::match(['put', 'patch'], '/products/{id}', [
        ProductController::class, 'update'
    ])->whereNumber('id');

    // ===== Transactions (Lely) =====
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/transactions/{id}', [TransactionController::class, 'show'])
        ->whereNumber('id');
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::match(['put', 'patch'], '/transactions/{id}', [
        TransactionController::class, 'update'
    ])->whereNumber('id');

    // ===== Product Reviews =====
    Route::get('/product-reviews', [ProductReviewController::class, 'index']);
    Route::get('/product-reviews/{id}', [ProductReviewController::class, 'show'])
        ->whereNumber('id');
    Route::post('/product-reviews', [ProductReviewController::class, 'store']);
    Route::match(['put', 'patch'], '/product-reviews/{id}', [
        ProductReviewController::class, 'update'
    ])->whereNumber('id');

    /*
    |--------------------------------------------------------------------------
    | ADMIN ONLY - DELETE
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')->group(function () {

        // Delete Categories
        Route::delete('/categories/{id}', [
            CategoryController::class, 'destroy'
        ])->whereNumber('id');

        // Delete Products
        Route::delete('/products/{id}', [
            ProductController::class, 'destroy'
        ])->whereNumber('id');

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
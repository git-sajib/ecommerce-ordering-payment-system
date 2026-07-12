<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\StripeWebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Authentication Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('auth')->group(function () {

        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('me', [AuthController::class, 'me']);
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Category Routes
    |--------------------------------------------------------------------------
    */

    // Public Routes
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/tree', [CategoryController::class, 'tree']);
    Route::get('categories/dfs', [CategoryController::class, 'dfs']);
    Route::get('categories/{category}', [CategoryController::class, 'show']);

    // Admin Routes
    Route::middleware(['auth:sanctum', 'admin'])->group(function () {

        Route::post('categories', [CategoryController::class, 'store']);
        Route::put('categories/{category}', [CategoryController::class, 'update']);
        Route::delete('categories/{category}', [CategoryController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Product Routes
    |--------------------------------------------------------------------------
    */

    // Public Routes
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{product}', [ProductController::class, 'show']);

    // Admin Routes
    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::post('products', [ProductController::class, 'store']);
        Route::put('products/{product}', [ProductController::class, 'update']);
        Route::delete('products/{product}', [ProductController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Order Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('orders', [OrderController::class, 'index']);
        Route::get('orders/{order}', [OrderController::class, 'show']);

        Route::post('orders', [OrderController::class, 'store']);
    });

    /*
    |--------------------------------------------------------------------------
    | Stripe Webhook
    |--------------------------------------------------------------------------
    */

    Route::post(
        'webhooks/stripe',
        StripeWebhookController::class
    );

    /*
    |--------------------------------------------------------------------------
    | bKash Webhook / Callback
    |--------------------------------------------------------------------------
    */



    /*
    |--------------------------------------------------------------------------
    | Payment Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth:sanctum')->group(function () {

        Route::get('payments', [PaymentController::class, 'index']);
        Route::get('payments/{payment}', [PaymentController::class, 'show']);

        Route::post('payments', [PaymentController::class, 'store']);
    });
});

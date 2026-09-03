<?php

use App\Http\Controllers\Api\Admin\AdminCategoryController;
use App\Http\Controllers\Api\Admin\AdminCustomerController;
use App\Http\Controllers\Api\Admin\AdminOrderController;
use App\Http\Controllers\Api\Admin\AdminProductController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\InquiryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ShowroomController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Auth
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

// Categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{slug}', [CategoryController::class, 'show']);

// Products
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/featured', [ProductController::class, 'featured']);
Route::get('/products/{slug}', [ProductController::class, 'show']);

// Showrooms & Gallery
Route::get('/showrooms', [ShowroomController::class, 'index']);
Route::get('/gallery', [GalleryController::class, 'index']);

// Inquiries / Leads
Route::post('/inquiries', [InquiryController::class, 'store']);

// Public Order Track & Checkout
Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders/track/{orderNumber}', [OrderController::class, 'track']);

/*
|--------------------------------------------------------------------------
| Protected Customer & Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::put('/auth/profile', [AuthController::class, 'updateProfile']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/user/orders', [OrderController::class, 'userOrders']);
});

/*
|--------------------------------------------------------------------------
| Protected Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Admin Products
    Route::apiResource('/products', AdminProductController::class);

    // Admin Categories
    Route::apiResource('/categories', AdminCategoryController::class);

    // Admin Orders
    Route::get('/orders', [AdminOrderController::class, 'index']);
    Route::get('/orders/{id}', [AdminOrderController::class, 'show']);
    Route::patch('/orders/{id}/status', [AdminOrderController::class, 'updateStatus']);

    // Admin Customers
    Route::get('/customers', [AdminCustomerController::class, 'index']);
});

<?php

use App\Http\Controllers\Api\Admin\AdminCategoryController;
use App\Http\Controllers\Api\Admin\AdminCouponController;
use App\Http\Controllers\Api\Admin\AdminCustomerController;
use App\Http\Controllers\Api\Admin\AdminExportController;
use App\Http\Controllers\Api\Admin\AdminGalleryController;
use App\Http\Controllers\Api\Admin\AdminInquiryController;
use App\Http\Controllers\Api\Admin\AdminMenuController;
use App\Http\Controllers\Api\Admin\AdminOrderController;
use App\Http\Controllers\Api\Admin\AdminProductController;
use App\Http\Controllers\Api\Admin\AdminReviewController;
use App\Http\Controllers\Api\Admin\AdminSettingController;
use App\Http\Controllers\Api\Admin\AdminShowroomController;
use App\Http\Controllers\Api\Admin\DashboardController;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\InquiryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SettingController;
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

// Coupons & Promotional Vouchers
Route::post('/coupons/validate', [CouponController::class, 'validateCoupon']);

// Product Reviews & Ratings
Route::get('/products/{productId}/reviews', [ReviewController::class, 'index']);
Route::post('/products/{productId}/reviews', [ReviewController::class, 'store']);

// Public Order Track & Checkout
Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders/track/{orderNumber}', [OrderController::class, 'track']);

// Dynamic Website Settings & Navigation Menus
Route::get('/settings', [SettingController::class, 'index']);
Route::get('/menus/{location}', [SettingController::class, 'getMenu']);

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

    // Admin Inquiries / Leads
    Route::get('/inquiries', [AdminInquiryController::class, 'index']);
    Route::get('/inquiries/{id}', [AdminInquiryController::class, 'show']);
    Route::patch('/inquiries/{id}/status', [AdminInquiryController::class, 'updateStatus']);
    Route::delete('/inquiries/{id}', [AdminInquiryController::class, 'destroy']);

    // Admin Gallery Showcase
    Route::apiResource('/gallery', AdminGalleryController::class);

    // Admin Showrooms
    Route::apiResource('/showrooms', AdminShowroomController::class);

    // Admin Coupons
    Route::apiResource('/coupons', AdminCouponController::class);

    // Admin Customer Reviews & Ratings
    Route::get('/reviews', [AdminReviewController::class, 'index']);
    Route::patch('/reviews/{id}/status', [AdminReviewController::class, 'updateStatus']);
    Route::delete('/reviews/{id}', [AdminReviewController::class, 'destroy']);

    // Business Data CSV Exports
    Route::get('/export/orders', [AdminExportController::class, 'exportOrders']);
    Route::get('/export/inquiries', [AdminExportController::class, 'exportInquiries']);
    Route::get('/export/customers', [AdminExportController::class, 'exportCustomers']);

    // Website Settings Hub
    Route::get('/settings', [AdminSettingController::class, 'index']);
    Route::post('/settings/batch', [AdminSettingController::class, 'batchUpdate']);

    // Navigation Menus Manager
    Route::get('/menus', [AdminMenuController::class, 'index']);
    Route::post('/menus/{id}/items', [AdminMenuController::class, 'storeItem']);
    Route::put('/menus/items/{id}', [AdminMenuController::class, 'updateItem']);
    Route::delete('/menus/items/{id}', [AdminMenuController::class, 'destroyItem']);
    Route::post('/menus/{id}/reorder', [AdminMenuController::class, 'reorder']);
});


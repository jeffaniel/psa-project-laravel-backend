<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

Route::middleware(['throttle:api'])->prefix('public')->group(function () {
    Route::get('/products', [ProductController::class, 'publicIndex']);
    Route::get('/products/{id}', [ProductController::class, 'publicShow']);
    Route::get('/categories', [CategoryController::class, 'publicIndex']);
    Route::get('/categories/{id}/products', [CategoryController::class, 'getProducts']);
});

Route::middleware(['auth:sanctum','throttle:api'])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
    });

    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);

    Route::middleware(['role:admin'])->prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
        Route::post('/{id}/assign-role', [UserController::class, 'assignRole']);
    });

    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::post('/', [ProductController::class, 'store']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
        Route::post('/{id}/upload-image', [ProductController::class, 'uploadImage']);
        Route::get('/{id}/variants', [ProductController::class, 'getVariants']);
        Route::post('/{id}/variants', [ProductController::class, 'addVariant']);
        Route::put('/variants/{variantId}', [ProductController::class, 'updateVariant']);
        Route::delete('/variants/{variantId}', [ProductController::class, 'deleteVariant']);
    });

    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });

    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{id}', [OrderController::class, 'show']);
        Route::put('/{id}', [OrderController::class, 'update']);
        Route::delete('/{id}', [OrderController::class, 'destroy']);
        Route::put('/{id}/status', [OrderController::class, 'updateStatus']);
        Route::get('/{id}/invoice', [OrderController::class, 'generateInvoice']);
        Route::post('/{id}/refund', [OrderController::class, 'processRefund']);
    });

    Route::prefix('customers')->group(function () {
        Route::get('/', [CustomerController::class, 'index']);
        Route::post('/', [CustomerController::class, 'store']);
        Route::get('/{id}', [CustomerController::class, 'show']);
        Route::put('/{id}', [CustomerController::class, 'update']);
        Route::delete('/{id}', [CustomerController::class, 'destroy']);
        Route::get('/{id}/orders', [CustomerController::class, 'getOrders']);
        Route::get('/{id}/analytics', [CustomerController::class, 'getAnalytics']);
    });

    Route::prefix('suppliers')->group(function () {
        Route::get('/', [SupplierController::class, 'index']);
        Route::post('/', [SupplierController::class, 'store']);
        Route::get('/{id}', [SupplierController::class, 'show']);
        Route::put('/{id}', [SupplierController::class, 'update']);
        Route::delete('/{id}', [SupplierController::class, 'destroy']);
        Route::get('/{id}/products', [SupplierController::class, 'getProducts']);
        Route::post('/{id}/purchase-orders', [SupplierController::class, 'createPurchaseOrder']);
    });

    Route::prefix('inventory')->group(function () {
        Route::get('/', [InventoryController::class, 'index']);
        Route::post('/stock-in', [InventoryController::class, 'stockIn']);
        Route::post('/stock-out', [InventoryController::class, 'stockOut']);
        Route::get('/low-stock', [InventoryController::class, 'lowStock']);
        Route::get('/movements', [InventoryController::class, 'movements']);
        Route::post('/adjust', [InventoryController::class, 'adjustStock']);
        Route::get('/valuation', [InventoryController::class, 'valuation']);
    });

    Route::prefix('sales')->group(function () {
        Route::get('/', [SalesController::class, 'index']);
        Route::post('/', [SalesController::class, 'store']);
        Route::get('/{id}', [SalesController::class, 'show']);
        Route::put('/{id}', [SalesController::class, 'update']);
        Route::delete('/{id}', [SalesController::class, 'destroy']);
        Route::get('/analytics', [SalesController::class, 'analytics']);
        Route::get('/trends', [SalesController::class, 'trends']);
    });

    Route::prefix('payments')->group(function () {
        Route::get('/', [PaymentController::class, 'index']);
        Route::post('/process', [PaymentController::class, 'process']);
        Route::get('/{id}', [PaymentController::class, 'show']);
        Route::post('/stripe/webhook', [PaymentController::class, 'stripeWebhook']);
        Route::post('/paypal/webhook', [PaymentController::class, 'paypalWebhook']);
        Route::post('/refund', [PaymentController::class, 'refund']);
    });

    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::put('/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::put('/read-all', [NotificationController::class, 'markAllAsRead']);
        Route::delete('/{id}', [NotificationController::class, 'destroy']);
        Route::post('/send', [NotificationController::class, 'send']);
    });

    Route::prefix('reports')->group(function () {
        Route::get('/sales', [ReportController::class, 'salesReport']);
        Route::get('/inventory', [ReportController::class, 'inventoryReport']);
        Route::get('/customers', [ReportController::class, 'customerReport']);
        Route::get('/products', [ReportController::class, 'productReport']);
        Route::get('/financial', [ReportController::class, 'financialReport']);
        Route::get('/export/{type}', [ReportController::class, 'exportReport']);
    });
});

Route::middleware(['auth:sanctum', 'role:customer'])->prefix('customer')->group(function () {
    Route::get('/orders', [OrderController::class, 'customerOrders']);
    Route::post('/orders', [OrderController::class, 'placeOrder']);
    Route::get('/profile', [CustomerController::class, 'profile']);
    Route::put('/profile', [CustomerController::class, 'updateProfile']);
    Route::get('/wishlist', [CustomerController::class, 'wishlist']);
    Route::post('/wishlist/{productId}', [CustomerController::class, 'addToWishlist']);
    Route::delete('/wishlist/{productId}', [CustomerController::class, 'removeFromWishlist']);
});

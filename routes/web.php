<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    AdminDashboardController,
    AdminProductController,
    AdminOrderController,
    AdminUserController,
    AdminCategoryController
};
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');
Route::get('/admin/register', [AuthController::class, 'showRegisterForm'])->name('admin.register');
Route::post('/admin/register', [AuthController::class, 'registerAdmin'])->name('admin.register.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::prefix('admin')->name('admin.')->middleware(['auth:web', 'role:admin'])->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', AdminProductController::class);
    Route::resource('orders', AdminOrderController::class);
    Route::put('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::put('orders/{order}/approve-payment', [AdminOrderController::class, 'approvePayment'])->name('orders.approve-payment');
    Route::put('orders/{order}/reject-payment', [AdminOrderController::class, 'rejectPayment'])->name('orders.reject-payment');
    Route::put('orders/{order}/mark-delivered', [AdminOrderController::class, 'markDelivered'])->name('orders.mark-delivered');
    Route::resource('users', AdminUserController::class);
    Route::resource('categories', AdminCategoryController::class);
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes - Aplikasi Pemesanan Makanan (LSP Serkom)
|--------------------------------------------------------------------------
*/

// 1. Sisi Customer (Publik / Tanpa Login)
Route::get('/', [OrderController::class, 'index'])->name('customer.index');
Route::post('/checkout', [OrderController::class, 'store'])->name('customer.checkout');

// 2. Sisi Admin (Terproteksi Middleware Auth)
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard Utama: Rekap Pesanan Masuk
    Route::get('/dashboard', [OrderController::class, 'adminDashboard'])->name('dashboard');
    Route::get('/admin/dashboard', [OrderController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::patch('/admin/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');

    // Master Data Makanan (Resource CRUD: index, create, store, edit, update, destroy)
    Route::resource('/admin/foods', FoodController::class);

    // Profile Settings (Breeze bawaan)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Autentikasi Laravel Breeze
require __DIR__.'/auth.php';

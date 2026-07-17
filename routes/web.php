<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard Route
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Scanner routes (must be before resource to avoid conflicts)
    Route::get('/items/scan', [ItemController::class, 'scan'])->name('items.scan');
    Route::get('/items/find-by-sku/{sku}', [ItemController::class, 'findBySku'])->name('items.find-by-sku');

    // Download QR Code
    Route::get('/items/{item}/download-qr', [ItemController::class, 'downloadQr'])->name('items.download-qr');

    // Record Stock Mutation
    Route::post('/items/{item}/stock', [StockController::class, 'store'])->name('items.stock.store');

    // Items CRUD
    Route::resource('items', ItemController::class);

    // Categories CRUD (Simplified)
    Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

Route::get('/', [OrderController::class, 'index'])->name('landing');
Route::post('/order', [OrderController::class, 'store'])->name('orders.store');
Route::get('/thanks', [OrderController::class, 'thanks'])->name('thanks');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

// Cart routes
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'show'])->name('cart.show');
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/count', [CartController::class, 'count'])->name('cart.count');
});

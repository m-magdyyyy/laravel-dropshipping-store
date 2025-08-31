<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;

Route::get('/', [OrderController::class, 'index'])->name('landing');
Route::post('/order', [OrderController::class, 'store'])->name('orders.store');
Route::get('/thanks', [OrderController::class, 'thanks'])->name('thanks');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

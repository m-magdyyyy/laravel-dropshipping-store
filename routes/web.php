<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductFeedController;
use App\Http\Controllers\SitemapController;

Route::get('/', [OrderController::class, 'index'])->name('landing');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/google-feed.xml', [ProductFeedController::class, 'generate'])->name('google.feed');
Route::post('/order', [OrderController::class, 'store'])->name('orders.store');
Route::get('/thanks', [OrderController::class, 'thanks'])->name('thanks');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

// Local debug route to inspect upload limits and Livewire temp upload config
if (app()->environment('local')) {
    Route::get('/_debug/upload', function () {
        return response()->json([
            'php' => [
                'version' => PHP_VERSION,
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size'),
                'memory_limit' => ini_get('memory_limit'),
                'file_uploads' => ini_get('file_uploads'),
            ],
            'livewire' => config('livewire.temporary_file_upload'),
            'filesystems' => [
                'default' => config('filesystems.default'),
                'public_url' => config('filesystems.disks.public.url'),
            ],
        ]);
    });
}

// Cart routes
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'show'])->name('cart.show');
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/count', [CartController::class, 'count'])->name('cart.count');
});

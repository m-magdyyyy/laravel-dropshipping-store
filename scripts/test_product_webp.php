<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing auto WebP conversion for products...\n";

try {
    // نسخ ملف اختبار
    $sourceFile = storage_path('app/public/products/KNP_4499.webp');
    $testFile = 'products/test-product-image.jpg';
    
    if (file_exists($sourceFile)) {
        // نسخ وإعادة تسمية كـ JPEG للاختبار
        copy($sourceFile, storage_path('app/public/' . $testFile));
        
        echo "Created test image: $testFile\n";
        
        // إنشاء منتج جديد
        $product = Product::create([
            'name' => 'منتج اختبار WebP',
            'description' => 'منتج لاختبار التحويل التلقائي إلى WebP',
            'price' => 100.00,
            'image' => $testFile,
            'is_active' => true,
        ]);
        
        echo "Created product ID: {$product->id}\n";
        echo "Original image path: {$product->image}\n";
        
        // انتظار لمعالجة Observer
        sleep(3);
        
        // إعادة تحميل البيانات
        $product->refresh();
        
        echo "Updated image path: {$product->image}\n";
        
        // التحقق من وجود ملف WebP
        if (Storage::disk('public')->exists($product->image) && str_contains($product->image, '.webp')) {
            echo "\n✅ SUCCESS: Image automatically converted to WebP!\n";
            echo "WebP file: {$product->image}\n";
            
            $webpSize = Storage::disk('public')->size($product->image);
            echo "WebP size: " . number_format($webpSize / 1024, 2) . " KB\n";
        } else {
            echo "\n❌ FAILED: Image was not converted to WebP\n";
            echo "Current image: {$product->image}\n";
        }
        
        // تنظيف
        if (Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        
    } else {
        echo "No source file found for testing\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
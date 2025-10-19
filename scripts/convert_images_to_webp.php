<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

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

echo "Starting image conversion to WebP...\n";

try {
    $manager = new ImageManager();
    
    // الحصول على جميع الصور في مجلد products
    $imageFiles = Storage::disk('public')->files('products');
    
    foreach ($imageFiles as $imagePath) {
        $extension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
        
        // تحويل فقط صور JPEG و PNG
        if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
            echo "Processing: {$imagePath}\n";
            
            // قراءة الصورة
            $imageContent = Storage::disk('public')->get($imagePath);
            if (!$imageContent) {
                echo "Could not read: {$imagePath}\n";
                continue;
            }
            
            // معالجة الصورة
            $img = $manager->make($imageContent);
            
            // تحويل إلى WebP مع جودة عالية
            $webpImage = (string) $img->encode('webp', 85);
            
            // إنشاء اسم الملف الجديد
            $pathInfo = pathinfo($imagePath);
            $webpPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
            
            // حفظ النسخة WebP
            Storage::disk('public')->put($webpPath, $webpImage);
            
            $originalSize = strlen($imageContent);
            $webpSize = strlen($webpImage);
            $savings = round((1 - $webpSize / $originalSize) * 100, 1);
            
            echo "Converted {$imagePath} -> {$webpPath}\n";
            echo "Size reduction: {$savings}% (from " . number_format($originalSize/1024, 1) . "KB to " . number_format($webpSize/1024, 1) . "KB)\n";
            
            // حذف الصورة الأصلية إذا كان التوفير أكبر من 30%
            if ($savings > 30) {
                Storage::disk('public')->delete($imagePath);
                echo "Deleted original file due to significant size reduction\n";
            }
            
            echo "---\n";
        }
    }
    
    echo "Image conversion completed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
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

echo "Optimizing large WebP images for web performance...\n";
echo "===================================================\n";

try {
    $manager = new ImageManager();
    
    // الحصول على الصور الكبيرة (أكثر من 500KB)
    $imageFiles = Storage::disk('public')->files('products');
    
    $processedCount = 0;
    $totalSavings = 0;
    
    foreach ($imageFiles as $imagePath) {
        $fileSize = Storage::disk('public')->size($imagePath);
        
        // معالجة الصور الكبيرة فقط (أكثر من 500KB)
        if ($fileSize > 500 * 1024) {
            echo "\nProcessing: " . basename($imagePath) . "\n";
            echo "Original size: " . number_format($fileSize / 1024, 1) . " KB\n";
            
            $imageContent = Storage::disk('public')->get($imagePath);
            if (!$imageContent) {
                echo "Could not read file, skipping...\n";
                continue;
            }
            
            $img = $manager->make($imageContent);
            
            // ضغط قوي للصور الكبيرة جداً
            if ($fileSize > 1024 * 1024) { // أكبر من 1MB
                echo "Large image detected - applying aggressive compression\n";
                
                // تصغير العرض إلى 800px كحد أقصى
                if ($img->width() > 800) {
                    $img->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    echo "Resized to: " . $img->width() . "x" . $img->height() . "\n";
                }
                
                // جودة منخفضة للضغط القوي
                $optimizedImage = (string) $img->encode('webp', 65);
                
            } else { // صور متوسطة الحجم
                echo "Medium image - applying moderate compression\n";
                
                // تصغير العرض إلى 1000px
                if ($img->width() > 1000) {
                    $img->resize(1000, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    echo "Resized to: " . $img->width() . "x" . $img->height() . "\n";
                }
                
                // جودة متوسطة
                $optimizedImage = (string) $img->encode('webp', 75);
            }
            
            $newSize = strlen($optimizedImage);
            $savings = $fileSize - $newSize;
            $savingsPercent = round(($savings / $fileSize) * 100, 1);
            
            // حفظ النسخة المحسنة
            Storage::disk('public')->put($imagePath, $optimizedImage);
            
            echo "New size: " . number_format($newSize / 1024, 1) . " KB\n";
            echo "Savings: " . number_format($savings / 1024, 1) . " KB ({$savingsPercent}%)\n";
            
            $totalSavings += $savings;
            $processedCount++;
        }
    }
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "Optimization completed!\n";
    echo "Images processed: {$processedCount}\n";
    echo "Total space saved: " . number_format($totalSavings / 1024 / 1024, 2) . " MB\n";
    echo "This should significantly improve website loading speed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
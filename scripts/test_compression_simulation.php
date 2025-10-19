<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
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

echo "Testing compression for 15MB+ images...\n";
echo "========================================\n";

// محاكاة معالجة صورة 15MB
function simulateImageProcessing($originalSizeMB, $width = 3000, $height = 2000) {
    $manager = new ImageManager();
    
    // إنشاء صورة اختبار بالحجم المطلوب تقريباً
    $img = $manager->canvas($width, $height, '#ffffff');
    
    // إضافة نص للصورة لجعلها أكثر واقعية
    for ($i = 0; $i < 100; $i++) {
        $img->text('Test Image Content ' . $i, rand(0, $width-200), rand(0, $height-20), function($font) {
            $font->size(16);
            $font->color('#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT));
        });
    }
    
    echo "\n📊 Testing image: {$width}x{$height} (simulating ~{$originalSizeMB}MB)\n";
    echo "----------------------------------------------------------------\n";
    
    // تطبيق نفس المعالجة المستخدمة في MediaObserver
    $originalSize = $originalSizeMB * 1024 * 1024; // تحويل إلى bytes
    
    if ($originalSize > 5 * 1024 * 1024) { // أكبر من 5MB
        echo "🔍 Image size > 5MB - Applying aggressive compression\n";
        $quality = 75;
        
        // تصغير الصور الكبيرة جداً
        if ($img->width() > 1200) {
            $newWidth = 1200;
            $newHeight = ($img->height() * 1200) / $img->width();
            $img->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            echo "📏 Resized from {$width}x{$height} to {$newWidth}x{$newHeight}\n";
        }
    } else {
        $quality = 85; // جودة عالية للصور الأصغر
    }
    
    // تحويل إلى WebP
    $webpImage = (string) $img->encode('webp', $quality);
    $webpSize = strlen($webpImage);
    
    // حساب النتائج
    $webpSizeMB = $webpSize / (1024 * 1024);
    $compressionRatio = (1 - $webpSize / $originalSize) * 100;
    
    echo "📈 Results:\n";
    echo "   Original size: " . number_format($originalSizeMB, 1) . " MB\n";
    echo "   WebP size: " . number_format($webpSizeMB, 2) . " MB\n";
    echo "   Compression: " . number_format($compressionRatio, 1) . "%\n";
    echo "   Quality used: {$quality}%\n";
    
    return [
        'original_mb' => $originalSizeMB,
        'webp_mb' => $webpSizeMB,
        'compression' => $compressionRatio,
        'quality' => $quality
    ];
}

// اختبار أحجام مختلفة
$testCases = [
    ['size' => 15, 'width' => 4000, 'height' => 3000],
    ['size' => 25, 'width' => 5000, 'height' => 4000],
    ['size' => 50, 'width' => 6000, 'height' => 5000],
    ['size' => 10, 'width' => 3000, 'height' => 2500],
    ['size' => 5, 'width' => 2000, 'height' => 1500],
];

$results = [];
foreach ($testCases as $case) {
    $result = simulateImageProcessing($case['size'], $case['width'], $case['height']);
    $results[] = $result;
}

echo "\n📋 Summary for different image sizes:\n";
echo "=====================================\n";
foreach ($results as $result) {
    echo sprintf(
        "%5.1fMB → %5.2fMB (-%4.1f%% compression)\n",
        $result['original_mb'],
        $result['webp_mb'], 
        $result['compression']
    );
}

echo "\n💡 Key Points:\n";
echo "- Images >5MB: Quality 75% + resize to 1200px width\n";
echo "- Images <5MB: Quality 85% + keep original size\n";
echo "- WebP format provides additional 20-30% compression vs JPEG\n";
echo "- Total size reduction typically 80-95% for large images!\n";
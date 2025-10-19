<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Awcodes\Curator\Models\Media;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

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

echo "Testing WebP auto-conversion...\n";

try {
    // إنشاء ملف اختبار (محاكاة رفع صورة)
    $testImagePath = storage_path('app/public/test-upload.jpg');
    
    // نسخ صورة موجودة للاختبار
    $existingImage = storage_path('app/public/products/1760856314_68f488fa04b6a.webp');
    if (file_exists($existingImage)) {
        copy($existingImage, $testImagePath);
        
        echo "Created test image: $testImagePath\n";
        
        // إنشاء media record جديد (هذا سيؤدي إلى تشغيل Observer)
        $media = Media::create([
            'disk' => 'public',
            'directory' => 'media',
            'visibility' => 'public',
            'name' => 'Test Upload',
            'path' => 'test-upload.jpg',
            'width' => null,
            'height' => null,
            'size' => filesize($testImagePath),
            'type' => 'image',
            'ext' => 'jpg',
        ]);
        
        echo "Created media record with ID: {$media->id}\n";
        
        // انتظار قصير لمعالجة الـ Observer
        sleep(2);
        
        // تحديث البيانات من قاعدة البيانات
        $media->refresh();
        
        echo "After processing:\n";
        echo "- Path: {$media->path}\n";
        echo "- Extension: {$media->ext}\n";
        echo "- Size: {$media->size} bytes\n";
        echo "- Disk: {$media->disk}\n";
        
        // تحقق من وجود ملف WebP
        if ($media->ext === 'webp' && Storage::disk('public')->exists($media->path)) {
            echo "\n✅ SUCCESS: Image successfully converted to WebP!\n";
            echo "WebP file exists at: {$media->path}\n";
        } else {
            echo "\n❌ FAILED: Image was not converted to WebP\n";
        }
        
        // تنظيف
        Storage::disk('public')->delete('test-upload.jpg');
        if (Storage::disk('public')->exists($media->path)) {
            Storage::disk('public')->delete($media->path);
        }
        $media->delete();
        
    } else {
        echo "No test image found to use for testing\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
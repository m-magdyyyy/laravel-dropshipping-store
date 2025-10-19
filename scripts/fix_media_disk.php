<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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

echo "Starting media disk fix script...\n";

try {
    // الحصول على جميع ملفات الميديا التي تستخدم s3_raw
    $mediaRecords = DB::table('media')->where('disk', 's3_raw')->get();
    
    echo "Found " . count($mediaRecords) . " media records with s3_raw disk\n";
    
    foreach ($mediaRecords as $media) {
        echo "Processing media ID: {$media->id}, path: {$media->path}\n";
        
        // البحث عن الملف في مجلد products
        $productFiles = Storage::disk('public')->files('products');
        $matchingFile = null;
        
        // البحث عن ملف مطابق (قد يكون الاسم مختلف قليلاً)
        foreach ($productFiles as $file) {
            $filename = basename($file);
            $mediaFilename = basename($media->path);
            
            // إزالة الامتدادات للمقارنة
            $fileWithoutExt = pathinfo($filename, PATHINFO_FILENAME);
            $mediaWithoutExt = pathinfo($mediaFilename, PATHINFO_FILENAME);
            
            // البحث عن تطابق جزئي في الاسم
            if (strpos($fileWithoutExt, substr($mediaWithoutExt, 0, 10)) !== false ||
                strpos($mediaWithoutExt, substr($fileWithoutExt, 0, 10)) !== false) {
                $matchingFile = $file;
                break;
            }
        }
        
        if ($matchingFile) {
            echo "Found matching file: {$matchingFile}\n";
            
            // تحديث بيانات الميديا
            DB::table('media')->where('id', $media->id)->update([
                'path' => $matchingFile,
                'disk' => 'public',
                'size' => Storage::disk('public')->size($matchingFile),
                'updated_at' => now()
            ]);
            
            echo "Updated media ID {$media->id} to use public disk with path: {$matchingFile}\n";
        } else {
            echo "No matching file found for media ID {$media->id}\n";
            
            // إذا لم نجد ملف مطابق، نحاول إنشاء ملف وهمي أو حذف الإدخال
            // أو يمكننا تركه كما هو مع disk محلي
            DB::table('media')->where('id', $media->id)->update([
                'disk' => 'public',
                'updated_at' => now()
            ]);
        }
    }
    
    echo "\nScript completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
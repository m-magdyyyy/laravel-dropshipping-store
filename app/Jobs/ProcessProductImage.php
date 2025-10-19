<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class ProcessProductImage implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public int $tries = 1;
    public int $timeout = 30; // 30 ثانية كحد أقصى

    public function __construct(
        public string $imagePath,
        public string $imageType
    ) {}

    public function handle(): void
    {
        // تقليل استهلاك الذاكرة للسيرفرات الصغيرة
        ini_set('memory_limit', '128M');

        Log::info('ProcessProductImage: Starting processing', [
            'image_path' => $this->imagePath,
            'image_type' => $this->imageType
        ]);

        try {
            // قراءة الصورة من التخزين المحلي
            if (!Storage::disk('public')->exists($this->imagePath)) {
                Log::warning('ProcessProductImage: Image file not found', ['path' => $this->imagePath]);
                return;
            }

            $imageContent = Storage::disk('public')->get($this->imagePath);
            if (!$imageContent) {
                throw new \Exception('Failed to read image content');
            }

            $originalSize = strlen($imageContent);
            Log::info('ProcessProductImage: Original size', ['size' => $originalSize]);

            // إذا كان الحجم أقل من 2MB، لا نعالجه
            if ($originalSize < 2 * 1024 * 1024) { // 2MB
                Log::info('ProcessProductImage: Image size is acceptable, skipping', ['size' => $originalSize]);
                return;
            }

            // إنشاء ImageManager للتعامل مع الصورة (استخدام صيغة Intervention v2)
            $manager = new ImageManager();
            $img = $manager->make($imageContent);

            // تحديد استراتيجية الضغط حسب حجم الصورة
            if ($originalSize > 15 * 1024 * 1024) { // أكبر من 15MB - ضغط قوي جداً
                $img->resize(400, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $quality = 50; // جودة منخفضة
                
            } elseif ($originalSize > 5 * 1024 * 1024) { // أكبر من 5MB - ضغط متوسط قوي
                $img->resize(600, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $quality = 60; // جودة متوسطة منخفضة
                
            } else { // من 2MB إلى 5MB - ضغط خفيف
                $img->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $quality = 75; // جودة جيدة
            }

            // تحويل إلى WebP مع الجودة المحددة (أفضل من JPEG)
            $processedImage = (string) $img->encode('webp', $quality);
            $processedSize = strlen($processedImage);

            // إنشاء اسم ملف جديد للصورة المضغوطة بتنسيق WebP
            $pathInfo = pathinfo($this->imagePath);
            $compressedPath = $pathInfo['dirname'] . '/compressed_' . $pathInfo['filename'] . '.webp';

            // حفظ الصورة المضغوطة
            Storage::disk('public')->put($compressedPath, $processedImage);

            // اختياري: حذف الصورة الأصلية إذا كان الضغط فعال (أكثر من 60%)
            $compressionRatio = (1 - $processedSize / $originalSize) * 100;
            if ($compressionRatio > 60) {
                Storage::disk('public')->delete($this->imagePath);
                Log::info('ProcessProductImage: Original image deleted due to high compression efficiency');
            }

            Log::info('ProcessProductImage: Successfully processed', [
                'image_path' => $this->imagePath,
                'image_type' => $this->imageType,
                'original_size' => $originalSize,
                'processed_size' => $processedSize,
                'compression_ratio' => round($compressionRatio, 2) . '%',
                'quality' => $quality,
                'compressed_path' => $compressedPath
            ]);

        } catch (\Throwable $e) {
            Log::error('ProcessProductImage: Processing failed', [
                'image_path' => $this->imagePath,
                'image_type' => $this->imageType,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
    }
}
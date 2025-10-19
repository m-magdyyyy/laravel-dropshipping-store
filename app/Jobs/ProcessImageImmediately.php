<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Awcodes\Curator\Models\Media;

class ProcessImageImmediately implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public int $tries = 1;
    public int $timeout = 30; // 30 ثانية كحد أقصى

    public function __construct(public int $mediaId) {}

    public function handle(): void
    {
        // تقليل استهلاك الذاكرة للسيرفرات الصغيرة
        ini_set('memory_limit', '128M');

        Log::info('ProcessImageImmediately: Starting immediate processing', ['media_id' => $this->mediaId]);

        $media = Media::find($this->mediaId);
        if (!$media) {
            Log::warning('ProcessImageImmediately: Media not found', ['media_id' => $this->mediaId]);
            return;
        }

        // تخطي المعالجة إذا كان الملف ليس صورة
        if ($media->type !== 'image') {
            Log::info('ProcessImageImmediately: Skipping non-image file', ['media_id' => $this->mediaId]);
            return;
        }

        try {
            // قراءة الصورة من القرص المحدد في الوسيط
            Log::info('ProcessImageImmediately: Reading from storage', [
                'path' => $media->path,
                'disk' => $media->disk
            ]);
            
            $sourceDisk = $media->disk ?: 'public';
            
            // تحقق من وجود الملف في القرص المحدد
            if (!Storage::disk($sourceDisk)->exists($media->path)) {
                Log::error('ProcessImageImmediately: File not found', [
                    'path' => $media->path,
                    'disk' => $sourceDisk
                ]);
                throw new \Exception("Image file not found at path: {$media->path} on disk: {$sourceDisk}");
            }
            
            // قراءة محتوى الصورة
            $imageContent = Storage::disk($sourceDisk)->get($media->path);
            
            if (!$imageContent) {
                throw new \Exception('Failed to read image content');
            }

            $originalSize = strlen($imageContent);
            Log::info('ProcessImageImmediately: Original size', ['size' => $originalSize]);

            // إذا كان حجم الصورة كبير (> 5MB)، قم بمعالجتها بشكل أكثر تحديدًا
            if ($originalSize > 5 * 1024 * 1024) { // 5MB
                Log::info('ProcessImageImmediately: Large image detected, processing aggressively');

                // إنشاء ImageManager للتعامل مع الصورة (استخدام صيغة Intervention v2)
                $manager = new ImageManager();

                // معالجة الصورة
                $img = $manager->make($imageContent);

                // تصغير الصورة إلى عرض أقل (600px للصور الكبيرة)
                $targetWidth = $img->width() > 1000 ? 600 : 300;
                $img->resize($targetWidth, null, function ($constraint) {
                    $constraint->aspectRatio();
                });

                // تحويل إلى WebP مع جودة جيدة (75%) - WebP أكثر كفاءة من JPEG
                $processedImage = (string) $img->encode('webp', 75);

                Log::info('ProcessImageImmediately: Image processed', [
                    'original_size' => $originalSize,
                    'processed_size' => strlen($processedImage),
                    'compression_ratio' => round((1 - strlen($processedImage) / $originalSize) * 100, 2) . '%'
                ]);
            } else {
                // الصور الصغيرة نسبياً تحتاج معالجة أقل
                $manager = new ImageManager();
                $img = $manager->make($imageContent);
                
                // تصغير متوسط (800px)
                if ($img->width() > 800) {
                    $img->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }
                
                // جودة عالية (85%) مع تنسيق WebP
                $processedImage = (string) $img->encode('webp', 85);
                Log::info('ProcessImageImmediately: Small image processed with moderate settings');
            }

            // إنشاء مسار جديد للصورة المعالجة بتنسيق WebP
            $pathInfo = pathinfo($media->path);
            $processedPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_optimized.webp';
            
            // تأكد من وجود المجلد
            $directory = dirname($processedPath);
            if ($directory && $directory !== '.' && !Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            // حفظ الصورة المعالجة في public storage
            $saved = Storage::disk('public')->put($processedPath, $processedImage);
            
            if (!$saved) {
                throw new \Exception('Failed to save processed image to storage');
            }

            // تحديث بيانات الوسيط
            $media->update([
                'path' => $processedPath,
                'disk' => 'public',
                'size' => strlen($processedImage),
                'ext' => 'webp',
            ]);

            Log::info('ProcessImageImmediately: Successfully processed', [
                'media_id' => $this->mediaId,
                'original_size' => $originalSize,
                'final_size' => strlen($processedImage),
                'compression_ratio' => round((1 - strlen($processedImage) / $originalSize) * 100, 2) . '%'
            ]);

        } catch (\Throwable $e) {
            Log::error('ProcessImageImmediately: Processing failed', [
                'media_id' => $this->mediaId,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
    }
}

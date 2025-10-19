<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
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
            // قراءة الصورة من S3 raw
            Log::info('ProcessImageImmediately: Reading from S3 raw', ['path' => $media->path]);
            $imageContent = Storage::disk('s3_raw')->get($media->path);
            Log::info('ProcessImageImmediately: Content loaded', ['size' => strlen($imageContent ?? 'null')]);

            if (!$imageContent) {
                throw new \Exception('Failed to read image content from S3 raw');
            }

            $originalSize = strlen($imageContent);
            Log::info('ProcessImageImmediately: Original size', ['size' => $originalSize]);

            // إذا كان حجم الصورة كبير (> 5MB)، قم بمعالجتها فوراً
            if ($originalSize > 5 * 1024 * 1024) { // 5MB
                Log::info('ProcessImageImmediately: Large image detected, processing immediately');

                // إنشاء ImageManager بسيط للسيرفرات الصغيرة
                $manager = new ImageManager();

                // معالجة بسيطة وسريعة للسيرفرات الصغيرة
                $img = $manager->make($imageContent);

                // تصغير بسيط فقط (800px بدلاً من 1200px)
                if ($img->width() > 800) {
                    $img->widen(800);
                }

                // تحويل إلى PNG بدلاً من WebP (أسرع وأخف على السيرفر)
                $processedImage = (string) $img->encode('png', 85);

                Log::info('ProcessImageImmediately: Image processed', [
                    'original_size' => $originalSize,
                    'processed_size' => strlen($processedImage),
                    'compression_ratio' => round((1 - strlen($processedImage) / $originalSize) * 100, 2) . '%'
                ]);
            } else {
                // الصور الصغيرة انقلها كما هي
                $processedImage = $imageContent;
                Log::info('ProcessImageImmediately: Small image, moving as-is');
            }

            // إنشاء اسم ملف جديد
            $newFilename = $media->id . '.png';
            $processedPath = 'processed/' . $newFilename;

            // حفظ الصورة المعالجة في S3 processed
            Storage::disk('s3_processed')->put($processedPath, $processedImage);

            // تحديث بيانات الوسيط
            $media->update([
                'path' => $processedPath,
                'disk' => 's3_processed',
                'size' => strlen($processedImage),
                'ext' => 'png',
            ]);

            // حذف الملف الأصلي من raw bucket
            Storage::disk('s3_raw')->delete($media->path);

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

            // في حالة فشل المعالجة، انقل الملف كما هو إلى processed
            try {
                $imageContent = Storage::disk('s3_raw')->get($media->path);
                $originalFilename = $media->id . '_original.' . $media->ext;
                $originalPath = 'originals/' . $originalFilename;

                Storage::disk('s3_processed')->put($originalPath, $imageContent);

                $media->update([
                    'path' => $originalPath,
                    'disk' => 's3_processed',
                ]);

                Storage::disk('s3_raw')->delete($media->path);

                Log::info('ProcessImageImmediately: Fallback - moved original file', [
                    'media_id' => $this->mediaId,
                    'path' => $originalPath
                ]);

            } catch (\Exception $fallbackError) {
                Log::error('ProcessImageImmediately: Fallback also failed', [
                    'media_id' => $this->mediaId,
                    'error' => $fallbackError->getMessage()
                ]);
            }
        }
    }
}

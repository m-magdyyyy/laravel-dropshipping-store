<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Awcodes\Curator\Models\Media;

class ProcessImageImmediately implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public int $tries = 1;
    public int $timeout = 30; // 30 ثانية كحد أقصى

    public function __construct(public int $mediaId) {}

    public function handle(): void
    {
        // زيادة الذاكرة قليلاً للصور الكبيرة (128MB)
        ini_set('memory_limit', '128M');

        Log::info('ProcessImageImmediately: Starting ultra-light processing', ['media_id' => $this->mediaId]);

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
            // قراءة الصورة من local storage
            Log::info('ProcessImageImmediately: Reading from local storage', ['path' => $media->path]);
            
            $imageContent = Storage::disk($media->disk)->get($media->path);
            Log::info('ProcessImageImmediately: Content loaded', [
                'size' => strlen($imageContent ?? 'null'),
                'is_string' => is_string($imageContent),
            ]);

            if (!$imageContent || strlen($imageContent) < 100) {
                throw new \Exception('Failed to read image content or content too small: ' . strlen($imageContent ?? 'null') . ' bytes');
            }

            $originalSize = strlen($imageContent);
            Log::info('ProcessImageImmediately: Original size', ['size' => $originalSize]);

            // معالجة فورية لجميع الصور (حتى الصغيرة) لتوفير المساحة
            Log::info('ProcessImageImmediately: Processing all images for maximum compression');

            // إنشاء صورة باستخدام Intervention Image v2
            $img = Image::make($imageContent);

            // تصغير أكثر عدوانية للضغط الأقصى
            $originalWidth = $img->width();

            if ($originalWidth > 1200) {
                // صور كبيرة جداً - تصغير جداً (600px بدلاً من 800px)
                $img->widen(600);
            } elseif ($originalWidth > 800) {
                // صور متوسطة - تصغير أكثر
                $img->widen(500);
            } elseif ($originalWidth > 400) {
                // صور صغيرة - تصغير معقول
                $img->widen(350);
            }
            // الصور الصغيرة جداً تبقى كما هي

            // تحويل إلى JPEG مع ضغط أقوى (60% جودة للضغط الأقصى)
            $processedImage = (string) $img->encode('jpg', 60);

            if (!$processedImage) {
                throw new \Exception('Failed to encode image to JPEG');
            }

            // إعداد اسم الملف الجديد
            $newFilename = $media->id . '.jpg';
            $processedPath = 'processed/' . $newFilename;

            // تنظيف الذاكرة
            $img->destroy();
            unset($img);
            unset($imageContent);

            Log::info('ProcessImageImmediately: About to save to storage', [
                'processed_path' => $processedPath,
                'processed_size' => strlen($processedImage)
            ]);

            // حفظ الصورة المعالجة في نفس الـ disk
            Storage::disk($media->disk)->put($processedPath, $processedImage);

            // حذف الملف الأصلي
            Storage::disk($media->disk)->delete($media->path);

            // تحديث بيانات الوسيط
            $media->update([
                'path' => $processedPath,
                'size' => strlen($processedImage),
                'ext' => 'jpg',
            ]);

            Log::info('ProcessImageImmediately: Successfully processed', [
                'media_id' => $this->mediaId,
                'original_size' => $originalSize,
                'final_size' => strlen($processedImage),
                'space_saved' => round(($originalSize - strlen($processedImage)) / 1024 / 1024, 2) . 'MB',
                'compression_ratio' => round((1 - strlen($processedImage) / $originalSize) * 100, 2) . '%'
            ]);

        } catch (\Throwable $e) {
            Log::error('ProcessImageImmediately: Processing failed', [
                'media_id' => $this->mediaId,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            // في حالة فشل المعالجة، احتفظ بالملف الأصلي
            try {
                $rawImageContent = Storage::disk($media->disk)->get($media->path);
                
                if (!$rawImageContent) {
                    throw new \Exception('Cannot read original file for fallback');
                }
                
                $originalFilename = $media->id . '_original.' . $media->ext;
                $originalPath = 'originals/' . $originalFilename;

                Storage::disk($media->disk)->put($originalPath, $rawImageContent);

                $media->update([
                    'path' => $originalPath,
                ]);

                Storage::disk($media->disk)->delete($media->path);

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

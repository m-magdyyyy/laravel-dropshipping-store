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

class ProcessLargeImageAsync implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public int $tries = 2;
    public int $timeout = 120; // 2 دقائق للصور الكبيرة

    public function __construct(public int $mediaId) 
    {
        $this->onQueue('large-images'); // استخدام queue منفصل للصور الكبيرة
    }

    public function handle(): void
    {
        // زيادة الذاكرة قليلاً للصور الكبيرة
        ini_set('memory_limit', '256M');

        Log::info('ProcessLargeImageAsync: Starting async processing for large image', ['media_id' => $this->mediaId]);

        $media = Media::find($this->mediaId);
        if (!$media) {
            Log::warning('ProcessLargeImageAsync: Media not found', ['media_id' => $this->mediaId]);
            return;
        }

        if ($media->type !== 'image') {
            Log::info('ProcessLargeImageAsync: Skipping non-image file', ['media_id' => $this->mediaId]);
            return;
        }

        try {
            $imageContent = Storage::disk($media->disk)->get($media->path);

            if (!$imageContent) {
                throw new \Exception('Failed to read image content');
            }

            $originalSize = strlen($imageContent);
            Log::info('ProcessLargeImageAsync: Processing very large image', [
                'size' => round($originalSize / 1024 / 1024, 2) . 'MB'
            ]);

            $img = Image::make($imageContent);

            $originalWidth = $img->width();

            // تصغير أكثر عدوانية للصور الكبيرة جداً (ضغط أقصى)
            if ($originalWidth > 2000) {
                $img->widen(600); // تصغير كبير جداً - 600px بدلاً من 1000px
            } elseif ($originalWidth > 1500) {
                $img->widen(500); // 500px بدلاً من 800px
            } else {
                $img->widen(400); // 400px بدلاً من 600px
            }

            // ضغط أقوى جداً للصور الكبيرة - JPEG بجودة 50%
            $processedImage = (string) $img->encode('jpg', 50);

            $img->destroy();
            unset($img);
            unset($imageContent);

            $newFilename = $media->id . '.jpg';
            $processedPath = 'processed/' . $newFilename;

            Storage::disk($media->disk)->put($processedPath, $processedImage);
            Storage::disk($media->disk)->delete($media->path);

            $media->update([
                'path' => $processedPath,
                'size' => strlen($processedImage),
                'ext' => 'jpg',
            ]);

            $spaceSaved = round(($originalSize - strlen($processedImage)) / 1024 / 1024, 2);
            $compressionRatio = round((1 - strlen($processedImage) / $originalSize) * 100, 2);

            Log::info('ProcessLargeImageAsync: Large image processed successfully', [
                'media_id' => $this->mediaId,
                'original_size' => round($originalSize / 1024 / 1024, 2) . 'MB',
                'final_size' => round(strlen($processedImage) / 1024 / 1024, 2) . 'MB',
                'space_saved' => $spaceSaved . 'MB',
                'compression_ratio' => $compressionRatio . '%'
            ]);

        } catch (\Throwable $e) {
            Log::error('ProcessLargeImageAsync: Processing failed', [
                'media_id' => $this->mediaId,
                'error' => $e->getMessage()
            ]);

            // Fallback - احتفظ بالملف الأصلي
            try {
                $imageContent = Storage::disk($media->disk)->get($media->path);
                $originalFilename = $media->id . '_original.' . $media->ext;
                $originalPath = 'originals/' . $originalFilename;

                Storage::disk($media->disk)->put($originalPath, $imageContent);
                Storage::disk($media->disk)->delete($media->path);
                
                $media->update(['path' => $originalPath]);

                Log::warning('ProcessLargeImageAsync: Fallback applied for large image', [
                    'media_id' => $this->mediaId
                ]);

            } catch (\Exception $fallbackError) {
                Log::error('ProcessLargeImageAsync: Fallback failed', [
                    'media_id' => $this->mediaId,
                    'error' => $fallbackError->getMessage()
                ]);
            }
        }
    }
}

<?php

namespace App\Observers;

use Awcodes\Curator\Models\Media;
use App\Jobs\ProcessImageImmediately;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;

class MediaObserver
{
    public function created(Media $media): void
    {
        // معالجة فورية للصور لتحويلها إلى WebP
        if ($media->type === 'image') {
            $this->convertToWebP($media);
        }
    }

    private function convertToWebP(Media $media): void
    {
        try {
            Log::info('MediaObserver: Converting image to WebP', ['media_id' => $media->id]);
            
            $sourceDisk = $media->disk ?: 'public';
            
            // تحقق من وجود الملف
            if (!Storage::disk($sourceDisk)->exists($media->path)) {
                Log::error('MediaObserver: File not found', ['path' => $media->path, 'disk' => $sourceDisk]);
                return;
            }
            
            // قراءة محتوى الصورة
            $imageContent = Storage::disk($sourceDisk)->get($media->path);
            if (!$imageContent) {
                Log::error('MediaObserver: Could not read image content', ['media_id' => $media->id]);
                return;
            }
            
            // إذا كانت الصورة بالفعل WebP، تخطي التحويل
            if (strtolower($media->ext) === 'webp') {
                Log::info('MediaObserver: Image already in WebP format', ['media_id' => $media->id]);
                return;
            }
            
            $manager = new ImageManager();
            $img = $manager->make($imageContent);
            
            // تحديد الجودة حسب حجم الصورة الأصلية
            $originalSize = strlen($imageContent);
            if ($originalSize > 5 * 1024 * 1024) { // أكبر من 5MB
                $quality = 75;
                // تصغير الصور الكبيرة جداً
                if ($img->width() > 1200) {
                    $img->resize(1200, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }
            } else {
                $quality = 85; // جودة عالية للصور الأصغر
            }
            
            // تحويل إلى WebP
            $webpImage = (string) $img->encode('webp', $quality);
            
            // إنشاء مسار جديد بتنسيق WebP
            $pathInfo = pathinfo($media->path);
            $directory = ($pathInfo['dirname'] !== '.' && $pathInfo['dirname'] !== '') ? $pathInfo['dirname'] . '/' : '';
            // تنظيف اسم الملف من المساحات والأحرف الخاصة
            $cleanFilename = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $pathInfo['filename']);
            $webpPath = $directory . $cleanFilename . '.webp';
            
            // تأكد من وجود المجلد
            $directory = dirname($webpPath);
            if ($directory && $directory !== '.' && !Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }
            
            // حفظ النسخة WebP
            $saved = Storage::disk('public')->put($webpPath, $webpImage);
            
            if ($saved) {
                // تحديث بيانات الميديا
                $media->update([
                    'path' => $webpPath,
                    'disk' => 'public',
                    'size' => strlen($webpImage),
                    'ext' => 'webp',
                ]);
                
                // حذف الملف الأصلي إذا كان في مكان مختلف
                $originalPath = $media->getOriginal('path');
                if ($sourceDisk !== 'public' || $originalPath !== $webpPath) {
                    Storage::disk($sourceDisk)->delete($originalPath);
                }
                
                $savings = round((1 - strlen($webpImage) / $originalSize) * 100, 1);
                Log::info('MediaObserver: Successfully converted to WebP', [
                    'media_id' => $media->id,
                    'original_size' => $originalSize,
                    'webp_size' => strlen($webpImage),
                    'savings' => $savings . '%',
                    'new_path' => $webpPath
                ]);
            } else {
                Log::error('MediaObserver: Failed to save WebP image', ['media_id' => $media->id]);
            }
            
        } catch (\Throwable $e) {
            Log::error('MediaObserver: WebP conversion failed', [
                'media_id' => $media->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
    }
}
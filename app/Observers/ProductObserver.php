<?php

namespace App\Observers;

use App\Models\Product;
use App\Jobs\ProcessImageImmediately;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;

class ProductObserver
{
    public function created(Product $product): void
    {
        $this->processProductImages($product);
    }

    public function updated(Product $product): void
    {
        // فقط إذا تم تغيير الصور
        if ($product->wasChanged(['image', 'gallery'])) {
            $this->processProductImages($product);
        }
    }

    private function processProductImages(Product $product): void
    {
        try {
            // معالجة الصورة الرئيسية
            if ($product->image) {
                $this->processImage($product->image, 'main_image');
            }

            // معالجة صور المعرض
            if ($product->gallery && is_array($product->gallery)) {
                foreach ($product->gallery as $index => $imagePath) {
                    $this->processImage($imagePath, "gallery_image_{$index}");
                }
            }

            Log::info('Product images processing started', [
                'product_id' => $product->id,
                'main_image' => $product->image,
                'gallery_count' => is_array($product->gallery) ? count($product->gallery) : 0
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to process product images', [
                'product_id' => $product->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function processImage(string $imagePath, string $imageType): void
    {
        try {
            // التحقق من وجود الملف
            if (!Storage::disk('public')->exists($imagePath)) {
                Log::warning('Image file not found', ['path' => $imagePath]);
                return;
            }

            // تخطي الملفات WebP (تجنب المعالجة المزدوجة)
            if (strtolower(pathinfo($imagePath, PATHINFO_EXTENSION)) === 'webp') {
                return;
            }

            // تحويل إلى WebP فوراً
            $this->convertToWebP($imagePath, $imageType);

        } catch (\Exception $e) {
            Log::error('Failed to process image', [
                'path' => $imagePath,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function convertToWebP(string $imagePath, string $imageType): void
    {
        try {
            $imageContent = Storage::disk('public')->get($imagePath);
            if (!$imageContent) {
                return;
            }

            $manager = new \Intervention\Image\ImageManager();
            $img = $manager->make($imageContent);
            
            $originalSize = strlen($imageContent);
            
            // تحديد معالجة حسب حجم الصورة
            if ($originalSize > 5 * 1024 * 1024) { // أكبر من 5MB
                $quality = 75;
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
            
            // إنشاء مسار جديد
            $pathInfo = pathinfo($imagePath);
            $webpPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
            
            // حفظ النسخة WebP
            Storage::disk('public')->put($webpPath, $webpImage);
            
            // تحديث مسار الصورة في المنتج (بدون تشغيل Observer مرة أخرى)
            $product = Product::withoutEvents(function() use ($imagePath, $webpPath) {
                $product = Product::where('image', $imagePath)
                            ->orWhere('gallery', 'like', '%' . $imagePath . '%')
                            ->first();
                
                if ($product) {
                    if ($product->image === $imagePath) {
                        $product->update(['image' => $webpPath]);
                    }
                    
                    if ($product->gallery && is_array($product->gallery)) {
                        $updatedGallery = array_map(function($path) use ($imagePath, $webpPath) {
                            return $path === $imagePath ? $webpPath : $path;
                        }, $product->gallery);
                        
                        $product->update(['gallery' => $updatedGallery]);
                    }
                }
                
                return $product;
            });
            
            // حذف الصورة الأصلية
            Storage::disk('public')->delete($imagePath);
            
            $savings = round((1 - strlen($webpImage) / $originalSize) * 100, 1);
            
            Log::info('Image converted to WebP', [
                'original' => $imagePath,
                'webp' => $webpPath,
                'original_size' => $originalSize,
                'webp_size' => strlen($webpImage),
                'savings' => $savings . '%',
                'type' => $imageType
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to convert image to WebP', [
                'path' => $imagePath,
                'error' => $e->getMessage()
            ]);
        }
    }
}
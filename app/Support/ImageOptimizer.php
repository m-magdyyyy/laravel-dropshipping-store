<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class ImageOptimizer
{
    public static function toWebp(string $disk, string $srcPath, int $width, int $quality = 80): ?string
    {
        if (!Storage::disk($disk)->exists($srcPath)) {
            return null;
        }
        $manager = new ImageManager(new Driver());
        $data = Storage::disk($disk)->get($srcPath);
        $img = $manager->read($data)->orientate()->scaleDown(width: $width)->toWebp($quality);
        return (string) $img;
    }
    
    /**
     * ضغط الصورة باستخدام Spatie Image Optimizer
     */
    public static function optimize(string $path): void
    {
        if (!file_exists($path)) {
            return;
        }
        
        $optimizerChain = OptimizerChainFactory::create();
        $optimizerChain->optimize($path);
    }
    
    /**
     * تصغير حجم الصورة وضغطها
     */
    public static function optimizeAndResize(string $path, int $maxWidth = 1920, int $maxHeight = 1920, int $quality = 85): void
    {
        if (!file_exists($path)) {
            return;
        }
        
        $imageInfo = getimagesize($path);
        
        if (!$imageInfo) {
            return;
        }
        
        list($width, $height) = $imageInfo;
        
        // إذا كانت الصورة أكبر من الحد الأقصى، قلل الحجم
        if ($width > $maxWidth || $height > $maxHeight) {
            $ratio = min($maxWidth / $width, $maxHeight / $height);
            $newWidth = (int)($width * $ratio);
            $newHeight = (int)($height * $ratio);
            
            $image = null;
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            
            switch ($imageInfo[2]) {
                case IMAGETYPE_JPEG:
                    $image = imagecreatefromjpeg($path);
                    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                    imagejpeg($newImage, $path, $quality);
                    break;
                case IMAGETYPE_PNG:
                    $image = imagecreatefrompng($path);
                    imagealphablending($newImage, false);
                    imagesavealpha($newImage, true);
                    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                    imagepng($newImage, $path, 8);
                    break;
                case IMAGETYPE_GIF:
                    $image = imagecreatefromgif($path);
                    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                    imagegif($newImage, $path);
                    break;
            }
            
            if ($image) {
                imagedestroy($image);
            }
            imagedestroy($newImage);
        }
        
        // ضغط الصورة
        self::optimize($path);
    }
}

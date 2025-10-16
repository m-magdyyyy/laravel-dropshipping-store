<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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
}

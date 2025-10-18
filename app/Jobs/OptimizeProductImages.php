<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use GuzzleHttp\Client;

class OptimizeProductImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public function __construct(public int $productId) {}

    public function handle(): void
    {
        ini_set('memory_limit', '512M');
        
        Log::info('OptimizeProductImages: Starting optimization', [
            'product_id' => $this->productId,
        ]);

        $product = Product::find($this->productId);
        if (!$product) {
            Log::warning('OptimizeProductImages: Product not found', ['product_id' => $this->productId]);
            return;
        }

        $manager = new ImageManager(new Driver());
        $http = new Client(['timeout' => 20, 'headers' => ['User-Agent' => 'Laravel-Image-Optimizer/1.0']]);

        // Process main image
        if ($product->image) {
            $path = ltrim($product->image, '/');
            if (Storage::disk('public')->exists($path)) {
                $uuid = (string) Str::uuid();
                $newPath = "products/{$uuid}.webp";
                $thumbPath = "products/thumbs/{$uuid}.webp";

                try {
                    $imageData = Storage::disk('public')->get($path);
                    
                    // Main image processing
                    $img = $manager->read($imageData)
                        ->scaleDown(width: 1200)
                        ->toWebp(80);
                    Storage::disk('public')->put($newPath, (string) $img);

                    // Thumbnail processing
                    $thumb = $manager->read($imageData)
                        ->scaleDown(width: 360)
                        ->toWebp(80);
                    Storage::disk('public')->put($thumbPath, (string) $thumb);

                    // Delete old file
                    if ($path !== $newPath) {
                        Storage::disk('public')->delete($path);
                    }

                    // Update model
                    $product->forceFill([
                        'image' => $newPath,
                        'thumbnail_path' => $thumbPath,
                    ])->saveQuietly();

                } catch (\Throwable $e) {
                    Log::error('OptimizeProductImages: main image processing failed', [
                        'product_id' => $product->id,
                        'error_message' => $e->getMessage(),
                    ]);
                }
            }
        }

        // Process gallery images
        if (is_array($product->gallery) && !empty($product->gallery)) {
            $newGallery = [];
            foreach ($product->gallery as $gPath) {
                if (!is_string($gPath) || str_ends_with($gPath, '.webp')) continue;

                try {
                    $uuid = (string) Str::uuid();
                    $newGalleryPath = "products/gallery/{$uuid}.webp";
                    
                    $gPath = ltrim($gPath, '/');
                    if (Storage::disk('public')->exists($gPath)) {
                        $imageData = Storage::disk('public')->get($gPath);
                        $img = $manager->read($imageData)
                            ->scaleDown(width: 1200)
                            ->toWebp(80);
                        Storage::disk('public')->put($newGalleryPath, (string) $img);
                        
                        Storage::disk('public')->delete($gPath);
                        $newGallery[] = $newGalleryPath;
                    }
                } catch (\Throwable $e) {
                    Log::error('OptimizeProductImages: gallery image processing failed', [
                        'product_id' => $product->id,
                        'gallery_file' => $gPath,
                        'error_message' => $e->getMessage(),
                    ]);
                    $newGallery[] = $gPath;
                }
            }
            $product->forceFill(['gallery' => $newGallery])->saveQuietly();
        }

        Log::info('OptimizeProductImages: Completed successfully', ['product_id' => $product->id]);
    }
}

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

    public int $tries = 1; // Fail fast; processing can be retried manually

    public function __construct(public int $productId) {}

    public function handle(): void
    {
        // زيادة حد الذاكرة مؤقتاً لهذه المهمة فقط (لمعالجة الصور الكبيرة)
        ini_set('memory_limit', '512M');
        
        Log::info('OptimizeProductImages: Starting optimization', [
            'product_id' => $this->productId,
            'memory_limit' => ini_get('memory_limit'),
            'memory_usage' => round(memory_get_usage(true) / 1024 / 1024, 2) . 'MB',
        ]);

        $product = Product::find($this->productId);
        if (!$product) {
            Log::warning('OptimizeProductImages: Product not found', [
                'product_id' => $this->productId,
            ]);
            return;
        }

        $manager = new ImageManager(new Driver());

        // Prepare HTTP client for remote downloads
        $http = new Client(['timeout' => 20, 'headers' => [
            'User-Agent' => 'Laravel-Image-Optimizer/1.0'
        ]]);

        // Process main image (local or remote)
        if ($product->image) {
            // If remote, download first
            if (str_starts_with($product->image, 'http')) {
                try {
                    $res = $http->get($product->image);
                    if ($res->getStatusCode() === 200) {
                        $uuid = (string) Str::uuid();
                        $newPath = "products/{$uuid}.webp";
                        $thumbPath = "products/thumbs/{$uuid}.webp";

                        $imageData = (string) $res->getBody();
                        $img = $manager->read($imageData)
                            ->orientate()
                            ->scaleDown(width: 1200)
                            ->toWebp(80);
                        Storage::disk('public')->put($newPath, (string) $img);

                        $thumb = $manager->read($imageData)
                            ->orientate()
                            ->scaleDown(width: 360)
                            ->toWebp(80);
                        Storage::disk('public')->put($thumbPath, (string) $thumb);

                        $product->forceFill([
                            'image' => $newPath,
                            'thumbnail_path' => $thumbPath,
                        ])->saveQuietly();
                    }
                } catch (\Throwable $e) {
                    Log::error('OptimizeProductImages: remote main image download failed', [
                        'product_id' => $product->id,
                        'url' => $product->image,
                        'error_message' => $e->getMessage(),
                        'error_code' => $e->getCode(),
                        'error_file' => $e->getFile(),
                        'error_line' => $e->getLine(),
                        'error_trace' => $e->getTraceAsString(),
                        'memory_usage' => round(memory_get_usage(true) / 1024 / 1024, 2) . 'MB',
                        'memory_peak' => round(memory_get_peak_usage(true) / 1024 / 1024, 2) . 'MB',
                    ]);
                }
            } else {
                // Local path
                $path = ltrim($product->image, '/');
                if (Storage::disk('public')->exists($path)) {
                    $uuid = (string) Str::uuid();
                    $newPath = "products/{$uuid}.webp";
                    $thumbPath = "products/thumbs/{$uuid}.webp";

                    try {
                        $imageData = Storage::disk('public')->get($path);
                        $img = $manager->read($imageData)
                            ->orientate()
                            ->scaleDown(width: 1200)
                            ->toWebp(80);

                        Storage::disk('public')->put($newPath, (string) $img);

                        $thumb = $manager->read($imageData)
                            ->orientate()
                            ->scaleDown(width: 360)
                            ->toWebp(80);
                        Storage::disk('public')->put($thumbPath, (string) $thumb);

                        // Remove old file if different
                        if ($path !== $newPath) {
                            Storage::disk('public')->delete($path);
                        }

                        // Update model silently to avoid infinite loop
                        $product->forceFill([
                            'image' => $newPath,
                            'thumbnail_path' => $thumbPath,
                        ])->saveQuietly();
                    } catch (\Throwable $e) {
                        Log::error('OptimizeProductImages: main image processing failed', [
                            'product_id' => $product->id,
                            'image_path' => $path,
                            'error_message' => $e->getMessage(),
                            'error_code' => $e->getCode(),
                            'error_file' => $e->getFile(),
                            'error_line' => $e->getLine(),
                            'error_trace' => $e->getTraceAsString(),
                            'memory_usage' => round(memory_get_usage(true) / 1024 / 1024, 2) . 'MB',
                            'memory_peak' => round(memory_get_peak_usage(true) / 1024 / 1024, 2) . 'MB',
                        ]);
                    }
                }
            }
        }

        // Process gallery images if exist
        if (is_array($product->gallery) && !empty($product->gallery)) {
            $newGallery = [];
            foreach ($product->gallery as $gPath) {
                if (!is_string($gPath)) {
                    continue;
                }
                try {
                    $uuid = (string) Str::uuid();
                    $newPath = "products/gallery/{$uuid}.webp";
                    if (str_starts_with($gPath, 'http')) {
                        // download remote image
                        $res = $http->get($gPath);
                        if ($res->getStatusCode() !== 200) {
                            $newGallery[] = $gPath;
                            continue;
                        }
                        $imageData = (string) $res->getBody();
                    } else {
                        $gPath = ltrim($gPath, '/');
                        if (!Storage::disk('public')->exists($gPath)) {
                            $newGallery[] = $gPath;
                            continue;
                        }
                        $imageData = Storage::disk('public')->get($gPath);
                    }

                    $img = $manager->read($imageData)
                        ->orientate()
                        ->scaleDown(width: 1200)
                        ->toWebp(80);
                    Storage::disk('public')->put($newPath, (string) $img);

                    // Delete old only if it was local
                    if (isset($gPath) && is_string($gPath) && !str_starts_with($gPath, 'http')) {
                        Storage::disk('public')->delete(ltrim($gPath, '/'));
                    }
                    $newGallery[] = $newPath;
                } catch (\Throwable $e) {
                    Log::error('OptimizeProductImages: gallery image processing failed', [
                        'product_id' => $product->id,
                        'gallery_file' => $gPath ?? 'unknown',
                        'error_message' => $e->getMessage(),
                        'error_code' => $e->getCode(),
                        'error_file' => $e->getFile(),
                        'error_line' => $e->getLine(),
                        'error_trace' => $e->getTraceAsString(),
                        'memory_usage' => round(memory_get_usage(true) / 1024 / 1024, 2) . 'MB',
                        'memory_peak' => round(memory_get_peak_usage(true) / 1024 / 1024, 2) . 'MB',
                    ]);
                    $newGallery[] = $gPath; // keep original on failure
                }
            }
            $product->forceFill(['gallery' => $newGallery])->saveQuietly();
        }

        Log::info('OptimizeProductImages: Completed successfully', [
            'product_id' => $product->id,
            'memory_peak' => round(memory_get_peak_usage(true) / 1024 / 1024, 2) . 'MB',
        ]);
    }
}

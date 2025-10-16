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
        $product = Product::find($this->productId);
        if (!$product) {
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
                    Log::warning('OptimizeProductImages: remote main image download failed', [
                        'product_id' => $product->id,
                        'url' => $product->image,
                        'error' => $e->getMessage(),
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
                        Log::warning('OptimizeProductImages main image failed', [
                            'product_id' => $product->id,
                            'error' => $e->getMessage(),
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
                    Log::warning('OptimizeProductImages gallery failed', [
                        'product_id' => $product->id,
                        'file' => $gPath,
                        'error' => $e->getMessage(),
                    ]);
                    $newGallery[] = $gPath; // keep original on failure
                }
            }
            $product->forceFill(['gallery' => $newGallery])->saveQuietly();
        }
    }
}

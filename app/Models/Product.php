<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'features',
        'price',
        'original_price',
        'image',
        'gallery',
        'is_active',
        'sort_order',
        'meta_title',
        'meta_description'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'is_active' => 'boolean',
        'gallery' => 'array',
    ];

    // Auto-generate slug from name
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
        
        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->getOriginal('slug'))) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    // Scope for active products
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Get discount percentage
    public function getDiscountPercentageAttribute()
    {
        if ($this->original_price && $this->original_price > $this->price) {
            return round((($this->original_price - $this->price) / $this->original_price) * 100);
        }
        return 0;
    }

    // Get formatted price
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0) . ' ج.م';
    }

    // Get formatted original price
    public function getFormattedOriginalPriceAttribute()
    {
        return $this->original_price ? number_format($this->original_price, 0) . ' ج.م' : null;
    }

    // Get the main product image URL
    public function getImageUrlAttribute()
    {
        $image = $this->image;

        if (! $image) {
            return 'https://via.placeholder.com/400x400?text=No+Image';
        }

        // If it's already an absolute URL, return it
        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            return $image;
        }

        // If it already starts with /storage return as-is
        if (str_starts_with($image, '/storage/')) {
            return $image;
        }

        // If it starts with storage/ (no leading slash), normalize
        if (str_starts_with($image, 'storage/')) {
            return '/' . ltrim($image, '/');
        }

        // If the file exists under storage/app/public/{image}, return the public storage URL
        $storagePath = storage_path('app/public/' . $image);
        if (file_exists($storagePath)) {
            return '/storage/' . ltrim($image, '/');
        }

        // Fallback: if the file exists in public/ (rare), return that path
        $publicPath = public_path($image);
        if (file_exists($publicPath)) {
            return '/' . ltrim($image, '/');
        }

        // Default placeholder if no image or file doesn't exist
        return 'https://via.placeholder.com/400x400?text=No+Image';
    }

    // Get gallery images as resolved URLs (handles absolute, /storage, storage/ and existing files)
    public function getGalleryUrlsAttribute()
    {
        $gallery = $this->gallery ?? [];
        $urls = [];

        foreach ($gallery as $image) {
            if (! $image) {
                continue;
            }

            if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
                $urls[] = $image;
                continue;
            }

            if (str_starts_with($image, '/storage/')) {
                $urls[] = $image;
                continue;
            }

            if (str_starts_with($image, 'storage/')) {
                $urls[] = '/' . ltrim($image, '/');
                continue;
            }

            $storagePath = storage_path('app/public/' . $image);
            if (file_exists($storagePath)) {
                $urls[] = '/storage/' . ltrim($image, '/');
                continue;
            }

            $publicPath = public_path($image);
            if (file_exists($publicPath)) {
                $urls[] = '/' . ltrim($image, '/');
                continue;
            }

            $urls[] = 'https://via.placeholder.com/400x400?text=No+Image';
        }

        return $urls;
    }

    // Relationship with orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

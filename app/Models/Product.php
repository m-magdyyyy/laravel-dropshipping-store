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
        'image_url',
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
        // If we have a stored image_url field, use it
        if (isset($this->attributes['image_url']) && $this->attributes['image_url']) {
            return $this->attributes['image_url'];
        }
        
        // If we have a local uploaded image, use it
        if ($this->image) {
            // Check if it's already a full URL (http/https)
            if (str_starts_with($this->image, 'http')) {
                return $this->image;
            }
            
            // If it's a local file path, create storage URL
            return '/storage/' . ltrim($this->image, '/');
        }

        // Default placeholder if no image
        return 'https://via.placeholder.com/400x400?text=No+Image';
    }

    // Relationship with orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

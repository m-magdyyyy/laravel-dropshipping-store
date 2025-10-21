<?php

namespace App\Models;

use App\Jobs\OptimizeProductImages;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $attributes = [
        'sort_order' => 0,
        'is_active' => true,
    ];
    protected $fillable = [
        'name',
        'slug',
        'description',
        'features',
        'price',
        'original_price',
        'image',
        'image_url',
        'thumbnail_path',
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
        'image' => 'string', // تغيير من integer إلى string لحفظ مسار الملف
    ];

    /**
     * Generate a unique slug for the product
     */
    protected static function generateSlug($name, $id = null)
    {
        // Try to generate slug from name
        $slug = Str::slug($name);
        
        // If slug is empty (Arabic text), use transliteration or generate from ID
        if (empty($slug)) {
            // Use a combination of transliterated text and timestamp
            $slug = 'product-' . time();
        }
        
        // Ensure uniqueness
        $originalSlug = $slug;
        $count = 1;
        
        while (true) {
            $query = static::where('slug', $slug);
            
            // Exclude current product if updating
            if ($id) {
                $query->where('id', '!=', $id);
            }
            
            if (!$query->exists()) {
                break;
            }
            
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        
        return $slug;
    }

    // Auto-generate slug from name
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = static::generateSlug($product->name);
            }
        });
        
        static::updating(function ($product) {
            // Always regenerate slug if it's empty or null
            if (empty($product->slug)) {
                $product->slug = static::generateSlug($product->name, $product->id);
            }
        });

        // Dispatch optimization job when image is created or updated
        // Note: Disabled since we're now using CuratorPicker which handles media management
        /*
        static::created(function ($product) {
            if ($product->image) {
                OptimizeProductImages::dispatch($product->id);
            }
        });

        static::updated(function ($product) {
            // Only optimize if image changed
            if ($product->isDirty('image') && $product->image) {
                OptimizeProductImages::dispatch($product->id);
            }
        });
        */
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
        
        // If we have an image path (new method), use it
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        
        // If we have a media relationship, use it (old method)
        if ($this->imageMedia) {
            return $this->imageMedia->url;
        }

        // Default placeholder if no image
        return 'https://via.placeholder.com/400x400?text=No+Image';
    }

    // Get thumbnail URL
    public function getThumbUrlAttribute()
    {
        // If we have thumbnail_path, use it
        if ($this->thumbnail_path) {
            // Check if it's already a full URL (http/https)
            if (str_starts_with($this->thumbnail_path, 'http')) {
                return $this->thumbnail_path;
            }
            
            // If it's a local file path, create storage URL
            return asset('storage/' . ltrim($this->thumbnail_path, '/'));
        }

        // Fallback to main image if no thumbnail
        return $this->image_url;
    }

    // Relationship with orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Relationship with main image media
    public function imageMedia()
    {
        return $this->belongsTo(Media::class, 'image');
    }

    // Relationship with gallery media
    public function galleryMedia()
    {
        return $this->belongsToMany(Media::class, 'product_gallery', 'product_id', 'media_id');
    }
}

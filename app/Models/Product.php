<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'original_price',
        'stock',
        'image',
        'gallery',
        'category_id',
        'badge',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'gallery' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->where('is_approved', true)->avg('rating') ?: 0;
    }

    public function getReviewsCountAttribute()
    {
        return $this->reviews()->where('is_approved', true)->count();
    }

    public function getImageUrlAttribute(): string
    {
        $path = $this->image;
        $default = asset('build/assets/images/placeholder-product.jpg');

        if (!$path) {
            return $default;
        }

        // If it's a full URL
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        // Otherwise use Laravel storage
        return asset('storage/' . ltrim($path, '/'));
    }

    public function getGalleryUrlsAttribute(): array
    {
        if (!is_array($this->gallery) || empty($this->gallery)) {
            return [];
        }

        return array_map(function ($g) {
            if (Str::startsWith($g, ['http://', 'https://'])) {
                return $g;
            }
            return asset('storage/' . ltrim($g, '/'));
        }, $this->gallery);
    }
}

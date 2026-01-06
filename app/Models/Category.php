<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'is_active',
        'sort_order',
    ];

    /**
     * Get the products for the category.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope a query to only include active categories.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getImageUrlAttribute(): string
    {
        $path = $this->image;
        $default = asset('images/no-image.jpg');

        if (!$path) {
            return $default;
        }

        // If external URL
        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }

        // Check if file exists in storage/public
        if (\Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }

        // Fallback to direct asset path
        return asset(ltrim($path, '/'));
    }
}

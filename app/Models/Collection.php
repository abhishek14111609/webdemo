<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    /**
     * The products that belong to the collection.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
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

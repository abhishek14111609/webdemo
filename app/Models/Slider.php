<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'button_text',
        'button_link',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get full URL for the slider image.
     */
    public function getImageUrlAttribute(): string
    {
        $path = $this->image;

        if (!$path) {
            return asset('images/no-image.jpg'); // fallback
        }

        // If external URL
        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }

        // Check if stored in public disk
        if (Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }

        return asset('images/no-image.jpg');
    }

    /**
     * Get full URL for the slider thumbnail.
     */
    public function getThumbnailUrlAttribute(): string
    {
        $path = $this->image;
        if (!$path) {
            return asset('images/no-image.jpg');
        }

        $info = pathinfo($path);
        $thumbPath = $info['dirname'] . '/thumbs/' . $info['basename'];

        if (Storage::disk('public')->exists($thumbPath)) {
            return asset('storage/' . $thumbPath);
        }

        return asset('images/no-image.jpg');
    }
}

<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'city', 'phone', 'gender', 'profile_pic', 'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Check if the user is an admin.
     */
    public function isAdmin()
    {
        return (bool) $this->is_admin;
    }
    
    /**
     * Get the cart associated with the user.
     */
    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }
    
    /**
     * Get the wishlist items for the user.
     */
    public function wishlistItems(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }
    
    /**
     * Check if a product is in the user's wishlist.
     */
    public function hasInWishlist($productId): bool
    {
        return $this->wishlistItems()->where('product_id', $productId)->exists();
    }
    
    /**
     * Get the reviews written by the user.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
    
    /**
     * Check if the user has already reviewed a product.
     */
    public function hasReviewed($productId): bool
    {
        return $this->reviews()->where('product_id', $productId)->exists();
    }

    /**
     * Get the profile picture URL.
     */
    public function getProfilePicUrlAttribute(): string
    {
        $path = $this->profile_pic;
        $default = asset('build/assets/images/avatar-placeholder.png');
        if (!$path) {
            return $default;
        }
        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }
        return asset('storage/' . ltrim($path, '/'));
    }
}

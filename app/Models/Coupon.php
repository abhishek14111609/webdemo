<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_purchase_amount',
        'max_discount_amount',
        'usage_limit',
        'used_count',
        'per_user_limit',
        'valid_from',
        'valid_until',
        'is_active',
        'description'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_purchase_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    // Validate if coupon can be used
    public function canBeUsed($userId, $cartTotal): array
    {
        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'Coupon is not active'];
        }

        $now = Carbon::now();
        if ($this->valid_from && $now->lt($this->valid_from)) {
            return ['valid' => false, 'message' => 'Coupon is not yet valid'];
        }
        if ($this->valid_until && $now->gt($this->valid_until)) {
            return ['valid' => false, 'message' => 'Coupon has expired'];
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return ['valid' => false, 'message' => 'Coupon usage limit reached'];
        }

        $userUsageCount = $this->usages()->where('user_id', $userId)->count();
        if ($userUsageCount >= $this->per_user_limit) {
            return ['valid' => false, 'message' => 'You have already used this coupon'];
        }

        if ($cartTotal < $this->min_purchase_amount) {
            return ['valid' => false, 'message' => 'Minimum purchase amount of ₹' . number_format($this->min_purchase_amount, 2) . ' required'];
        }

        return ['valid' => true, 'message' => 'Coupon is valid'];
    }

    // Calculate discount amount
    public function calculateDiscount($cartTotal): float
    {
        if ($this->type === 'fixed') {
            return min($this->value, $cartTotal);
        }

        $discount = ($cartTotal * $this->value) / 100;

        if ($this->max_discount_amount) {
            $discount = min($discount, $this->max_discount_amount);
        }

        return round($discount, 2);
    }

    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('valid_from')->orWhere('valid_from', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('valid_until')->orWhere('valid_until', '>=', now());
            });
    }
}

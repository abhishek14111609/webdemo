<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'total',
        'tax',
        'discount',
        'coupon_discount',
        'shipping_cost',
        'subtotal',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'coupon_discount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function calculateTotals(): void
    {
        $this->subtotal = $this->items->sum('subtotal');
        $this->total = $this->subtotal
            + $this->tax
            + $this->shipping_cost
            - $this->discount
            - $this->coupon_discount;
        $this->save();
    }

    public function clear(): void
    {
        $this->items()->delete();
        $this->update([
            'subtotal' => 0,
            'total' => 0,
            'tax' => 0,
            'discount' => 0,
            'coupon_discount' => 0,
        ]);
    }
}

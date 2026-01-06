<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
        'options',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'quantity' => 'integer',
        'options' => 'array',
    ];

    /**
     * Get the cart that owns the cart item.
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }
    
    /**
     * Get the product that owns the cart item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calculate the subtotal for this cart item.
     */
    public function calculateSubtotal(): void
    {
        $this->subtotal = $this->price * $this->quantity;
        $this->save();
        $this->cart->calculateTotals();
    }

    /**
     * Update the quantity of this cart item.
     */
    public function updateQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
        $this->calculateSubtotal();
    }
}

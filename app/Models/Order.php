<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'subtotal',
        'tax',
        'total',
        'name',
        'email',
        'phone',
        'address',
        'country',
        'payment_method',
        'payment_id',
        'status',
        'tracking_number',
        'shipped_at',
        'delivered_at'
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    // Relationship to order items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relationship to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to status history
    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class)->latest();
    }
}

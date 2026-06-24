<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_number', 'email', 'first_name', 'last_name',
        'address', 'apartment', 'city', 'postal_code', 'phone',
        'payment_method', 'payment_status', 'subtotal', 'shipping', 'total', 'status', 'delivered_at', 'refund_reason',
        'coupon_code', 'discount_amount'
    ];

    protected $casts = [
        'delivered_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}

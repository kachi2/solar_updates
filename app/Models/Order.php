<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'order_No',
        'external_ref',
        'payment_ref',
        'payment_method',
        'amount',
        'shipping_id',
        'is_delivered',
        'is_paid',
        'dispatch_status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function shipping(): BelongsTo
    {
        return $this->belongsTo(Shipping::class, 'shipping_id');
    }

    /**
     * Lines of this order (order_items are linked by order number, not id).
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_No', 'order_No');
    }
}

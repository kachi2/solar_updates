<?php

namespace App\Models;

use App\Enums\DispatchStatus;
use App\Enums\OrderProgress;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
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

    // ---- Statuses (columns hold legacy ints; these return enums) ----------

    public function paymentStatus(): PaymentStatus
    {
        return PaymentStatus::fromLegacy($this->is_paid);
    }

    public function orderStatus(): OrderStatus
    {
        return OrderStatus::fromLegacy($this->is_delivered);
    }

    public function dispatchStatus(): DispatchStatus
    {
        return DispatchStatus::fromLegacy($this->dispatch_status);
    }

    /**
     * The one delivery status shown to customers.
     */
    public function progress(): OrderProgress
    {
        return OrderProgress::for($this->orderStatus(), $this->dispatchStatus());
    }

    public function isPaid(): bool
    {
        return $this->paymentStatus() === PaymentStatus::Paid;
    }

    public function paymentMethodLabel(): string
    {
        return PaymentMethod::labelFor($this->payment_method);
    }
}

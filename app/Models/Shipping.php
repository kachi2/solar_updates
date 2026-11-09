<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipping extends Model
{
    use HasFactory;

    protected $table = 'shippings';

    protected $fillable = [
        'user_id',
        'order_No',
        'receiver_name',
        'receiver_email',
        'receiver_phone',
        'address',
        'city',
        'state',
        'country',
        'lat',
        'lng',
        'zip_code',
        'delivery_method',
        'is_default',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class, 'shipping_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'shipping_id');
    }

    /**
     * The customer's default delivery address (the one checkout uses).
     */
    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', 1);
    }

    public function isDefault(): bool
    {
        return (int) $this->is_default === 1;
    }
}

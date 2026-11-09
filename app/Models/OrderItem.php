<?php

namespace App\Models;

use App\Models\Concerns\HasImage;
use App\Support\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory, HasImage;

    protected $table = 'order_items';

    protected $fillable = [
        'user_id',
        'order_No',
        'product_name',
        'price',
        'qty',
        'payable',
        'image',
        'design_image',
        'design_fee',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected function imageFolder(): string
    {
        return Media::PRODUCTS;
    }
}

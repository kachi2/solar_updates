<?php

namespace App\Models;

use App\Enums\Visibility;
use App\Models\Concerns\HasImage;
use App\Models\Concerns\HasSlug;
use App\Support\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, HasImage, HasSlug;

    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'name',
        'price',
        'sale_price',
        'admin_id',
        'image',
        'gallery',
        'status',
        'percentage',
        'description',
        'design_fee',
        'views',
        'order_count',
        'deleted_at',
        'sub_category_id',
        'specification',
        'exchange_rate',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Products shown in the shop: active, and not in a deactivated category.
     */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->whereNotIn('category_id', Category::query()->where('is_active', false)->select('id'));
    }

    public function visibility(): Visibility
    {
        return $this->is_active === false || $this->category?->is_active === false
            ? Visibility::Hidden
            : Visibility::Visible;
    }

    /**
     * No price set: the shop shows "Learn more" and WhatsApp instead of the cart buttons.
     */
    public function hasPrice(): bool
    {
        return (float) $this->sale_price > 0;
    }

    /**
     * Added in the last 30 days: product cards show a "New" badge (unless there is a discount badge).
     */
    public function isNew(): bool
    {
        return $this->created_at !== null && $this->created_at->gt(now()->subDays(30));
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function productVariations(): HasMany
    {
        return $this->hasMany(ProductVariation::class, 'product_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    protected function imageFolder(): string
    {
        return Media::PRODUCTS;
    }
}

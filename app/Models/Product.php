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
    ];

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Products not disabled in the admin (see Visibility).
     */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where(fn ($q) => $q->whereNull('status')->orWhere('status', '!=', Visibility::Hidden->value));
    }

    public function visibility(): Visibility
    {
        return Visibility::fromLegacy($this->status);
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

<?php

namespace App\Models;

use App\Models\Concerns\HasImage;
use App\Models\Concerns\HasSlug;
use App\Support\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory, HasImage, HasSlug;

    /**
     * route('user.category', $category) builds /category/{slug}.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'image',
        'deleted_at',
    ];

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function subCategories(): HasMany
    {
        return $this->hasMany(SubCategory::class, 'category_id');
    }

    protected function imageFolder(): string
    {
        return Media::CATEGORIES;
    }
}

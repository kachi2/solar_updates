<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    use HasFactory, HasSlug;

    protected $table = 'news';

    /**
     * Articles are titled, not named: /news/{slug-from-title}.
     */
    public function slugSource(): string
    {
        return 'title';
    }

    /**
     * Articles visible on the site (status 1 = disabled in the admin).
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where(fn ($q) => $q->whereNull('status')->orWhere('status', '!=', 1));
    }

    protected $fillable = [
        'title',
        'content',
        'image',
        'images',
        'admin_id',
        'status',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}

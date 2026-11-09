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

class News extends Model
{
    use HasFactory, HasImage, HasSlug;

    protected $table = 'news';

    /**
     * Articles are titled, not named: /news/{slug-from-title}.
     */
    public function slugSource(): string
    {
        return 'title';
    }

    /**
     * Articles visible on the site (see Visibility).
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where(fn ($q) => $q->whereNull('status')->orWhere('status', '!=', Visibility::Hidden->value));
    }

    public function visibility(): Visibility
    {
        return Visibility::fromLegacy($this->status);
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

    protected function imageFolder(): string
    {
        return Media::NEWS;
    }
}

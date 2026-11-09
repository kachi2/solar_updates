<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Gives a model a unique URL slug built from its `name` (or the attribute
 * returned by slugSource()). An existing slug is kept when the model is
 * renamed, so shared links keep working.
 */
trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::saving(function (self $model) {
            if (blank($model->slug)) {
                $model->slug = static::uniqueSlug((string) $model->getAttribute($model->slugSource()), $model->getKey());
            }
        });
    }

    /**
     * Attribute the slug is built from.
     */
    public function slugSource(): string
    {
        return 'name';
    }

    public static function uniqueSlug(string $name, mixed $ignoreId = null): string
    {
        $base = Str::slug($name) ?: Str::snake(class_basename(static::class), '-');
        $slug = $base;

        for ($i = 2; static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists(); $i++) {
            $slug = $base.'-'.$i;
        }

        return $slug;
    }
}

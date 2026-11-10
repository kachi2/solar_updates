<?php

namespace App\Models\Concerns;

use App\Support\Media;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * Adds an `image_url` attribute for models with an `image` file-name column.
 * The using model declares its upload folder via imageFolder() (a Media::* constant).
 */
trait HasImage
{
    abstract protected function imageFolder(): string;

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => Media::url($this->imageFolder(), $this->image));
    }

    /**
     * Web-sized copy of the image (≤ $width px), or the original if none exists yet.
     */
    public function imageSized(int $width): ?string
    {
        return Media::sized($this->imageFolder(), $this->image, $width);
    }
}

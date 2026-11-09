<?php

namespace App\Support;

/**
 * Upload folders under public/images and their public URLs.
 * Database columns store only the file name.
 */
final class Media
{
    public const PRODUCTS = 'products';

    public const CATEGORIES = 'category';

    public const NEWS = 'news';

    public const PROJECTS = 'projects';

    public const SLIDERS = 'sliders';

    public const SPECIFICATIONS = 'pdf';

    /**
     * Public URL of an uploaded file, or null when there is no file name.
     * File names are URL-encoded (some contain "+", which servers read as a space).
     */
    public static function url(string $folder, ?string $file): ?string
    {
        if ($file === null || $file === '') {
            return null;
        }

        return asset('images/'.$folder.'/'.rawurlencode($file));
    }
}

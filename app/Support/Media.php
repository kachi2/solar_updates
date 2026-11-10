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
     * Web-sized copies made for each folder (max widths in px), see ImageVariants.
     * Pick the smallest width that still looks sharp where the image is shown.
     */
    public const WIDTHS = [
        self::PRODUCTS => [480],
        self::CATEGORIES => [240],
        self::NEWS => [480, 1200],
        self::PROJECTS => [480, 1200],
        self::SLIDERS => [800, 1600],
    ];

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

    /**
     * URL of the web-sized copy (≤ $width px wide), falling back to the original
     * when the copy has not been generated (php artisan media:variants).
     */
    public static function sized(string $folder, ?string $file, int $width): ?string
    {
        if ($file === null || $file === '') {
            return null;
        }

        if (is_file(ImageVariants::path($folder, $file, $width))) {
            return asset('images/'.$folder.'/w'.$width.'/'.rawurlencode($file).'.webp');
        }

        return self::url($folder, $file);
    }
}

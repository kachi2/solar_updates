<?php

namespace App\Support;

/**
 * Web-sized WebP copies of uploaded images, stored next to the originals:
 *   public/images/{folder}/w{width}/{file}.webp
 * Originals are never modified. Pages fall back to the original when a copy is missing.
 */
final class ImageVariants
{
    private const QUALITY = 80;

    /**
     * Create all configured widths for one uploaded file.
     *
     * @return int number of copies written
     */
    public static function generate(string $folder, string $file): int
    {
        $source = public_path('images/'.$folder.'/'.$file);
        $written = 0;

        foreach (Media::WIDTHS[$folder] ?? [] as $width) {
            if (self::make($source, self::path($folder, $file, $width), $width)) {
                $written++;
            }
        }

        return $written;
    }

    public static function path(string $folder, string $file, int $width): string
    {
        return public_path('images/'.$folder.'/w'.$width.'/'.$file.'.webp');
    }

    private static function make(string $source, string $destination, int $maxWidth): bool
    {
        $info = @getimagesize($source);

        if ($info === false || ! function_exists('imagewebp')) {
            return false; // not an image (e.g. a video in the projects folder)
        }

        [$width, $height, $type] = $info;

        $image = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($source),
            IMAGETYPE_PNG => @imagecreatefrompng($source),
            IMAGETYPE_WEBP => @imagecreatefromwebp($source),
            IMAGETYPE_GIF => @imagecreatefromgif($source),
            default => false,
        };

        if (! $image) {
            return false;
        }

        $targetWidth = min($width, $maxWidth);
        $targetHeight = (int) round($height * $targetWidth / $width);

        $canvas = imagecreatetruecolor($targetWidth, $targetHeight);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        imagefill($canvas, 0, 0, imagecolorallocatealpha($canvas, 0, 0, 0, 127));
        imagecopyresampled($canvas, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

        if (! is_dir(dirname($destination))) {
            mkdir(dirname($destination), 0755, true);
        }

        $ok = imagewebp($canvas, $destination, self::QUALITY);

        imagedestroy($image);
        imagedestroy($canvas);

        return $ok;
    }
}

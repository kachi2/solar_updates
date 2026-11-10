<?php

namespace App\Services;

use App\Support\ImageVariants;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Stores uploads under public/images/{directory}, keeping the old app's layout
 * so existing database filenames keep working. Resizing uses PHP's GD extension.
 */
class ImageUploadService
{
    /**
     * Store an image, optionally resized to exactly $width x $height.
     * Returns the stored filename (what the database column holds).
     */
    public function store(UploadedFile $file, string $directory, ?int $width = null, ?int $height = null): string
    {
        $fileName = $this->fileName($file);
        $target = $this->directory($directory);

        if ($width === null || $height === null) {
            $file->move($target, $fileName);
        } else {
            $this->resize($file->getRealPath(), $target.DIRECTORY_SEPARATOR.$fileName, $width, $height);
        }

        // Web-sized WebP copies used by the shop pages (see Media::WIDTHS).
        ImageVariants::generate(trim($directory, '/'), $fileName);

        return $fileName;
    }

    /**
     * Store several images; returns their filenames in upload order.
     *
     * @param  array<int, UploadedFile>  $files
     * @return list<string>
     */
    public function storeMany(array $files, string $directory, ?int $width = null, ?int $height = null): array
    {
        return array_values(array_map(fn (UploadedFile $file) => $this->store($file, $directory, $width, $height), $files));
    }

    /**
     * Store any file (e.g. a PDF specification) without processing it.
     */
    public function storeFile(UploadedFile $file, string $directory): string
    {
        $fileName = $this->fileName($file);
        $file->move($this->directory($directory), $fileName);

        return $fileName;
    }

    private function fileName(UploadedFile $file): string
    {
        $extension = strtolower($file->guessExtension() ?: $file->getClientOriginalExtension());

        return time().'_'.Str::random(10).'.'.$extension;
    }

    private function directory(string $directory): string
    {
        $path = public_path('images/'.trim($directory, '/'));

        if (! is_dir($path) && ! mkdir($path, 0755, true) && ! is_dir($path)) {
            throw new RuntimeException("Unable to create upload directory [{$path}].");
        }

        return $path;
    }

    private function resize(string $source, string $destination, int $width, int $height): void
    {
        [$sourceWidth, $sourceHeight, $type] = getimagesize($source) ?: [0, 0, null];

        $image = match ($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($source),
            IMAGETYPE_PNG => imagecreatefrompng($source),
            IMAGETYPE_WEBP => imagecreatefromwebp($source),
            IMAGETYPE_GIF => imagecreatefromgif($source),
            default => throw new RuntimeException('Unsupported image type.'),
        };

        $canvas = imagecreatetruecolor($width, $height);

        if (in_array($type, [IMAGETYPE_PNG, IMAGETYPE_WEBP, IMAGETYPE_GIF], true)) {
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
            imagefill($canvas, 0, 0, imagecolorallocatealpha($canvas, 0, 0, 0, 127));
        }

        imagecopyresampled($canvas, $image, 0, 0, 0, 0, $width, $height, $sourceWidth, $sourceHeight);

        match ($type) {
            IMAGETYPE_JPEG => imagejpeg($canvas, $destination, 90),
            IMAGETYPE_PNG => imagepng($canvas, $destination),
            IMAGETYPE_WEBP => imagewebp($canvas, $destination, 90),
            IMAGETYPE_GIF => imagegif($canvas, $destination),
        };

        imagedestroy($image);
        imagedestroy($canvas);
    }
}

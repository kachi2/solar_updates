<?php

namespace App\Console\Commands;

use App\Support\ImageVariants;
use App\Support\Media;
use Illuminate\Console\Command;

/**
 * Creates web-sized WebP copies of existing uploads (new uploads get them
 * automatically via ImageUploadService). Safe to re-run; originals untouched.
 */
class GenerateImageVariants extends Command
{
    protected $signature = 'media:variants {--folder= : Only this upload folder (e.g. category)} {--force : Rebuild copies that already exist}';

    protected $description = 'Create web-sized WebP copies of uploaded images (originals are kept)';

    public function handle(): int
    {
        $folders = $this->option('folder') ? [$this->option('folder')] : array_keys(Media::WIDTHS);
        $before = $after = 0;

        foreach ($folders as $folder) {
            $files = array_filter(glob(public_path('images/'.$folder.'/*')) ?: [], 'is_file');
            $made = 0;

            foreach ($files as $path) {
                $file = basename($path);
                $widths = Media::WIDTHS[$folder] ?? [];
                $missing = array_filter($widths, fn ($w) => $this->option('force') || ! is_file(ImageVariants::path($folder, $file, $w)));

                if ($missing === [] || ! @getimagesize($path)) {
                    continue;
                }

                $made += ImageVariants::generate($folder, $file);
                $before += filesize($path);
                $after += filesize(ImageVariants::path($folder, $file, min($widths))) ?: 0;
            }

            $this->line(sprintf('%-10s %d copies written', $folder, $made));
        }

        if ($before > 0) {
            $this->info(sprintf(
                'Smallest copies are %.1f MB vs %.1f MB of originals (%d%% smaller).',
                $after / 1048576,
                $before / 1048576,
                100 - (int) round($after / $before * 100),
            ));
        }

        return self::SUCCESS;
    }
}

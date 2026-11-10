<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Support\ImageVariants;
use App\Support\Media;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ImageVariantsTest extends TestCase
{
    use RefreshDatabase;

    private string $publicPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->publicPath = sys_get_temp_dir().'/sofar-variants-'.uniqid();
        File::makeDirectory($this->publicPath.'/images/category', 0755, true);
        $this->app->usePublicPath($this->publicPath);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->publicPath);

        parent::tearDown();
    }

    private function makePhoto(string $name, int $width, int $height): void
    {
        $image = imagecreatetruecolor($width, $height);
        imagefill($image, 0, 0, imagecolorallocate($image, 200, 120, 40));
        imagejpeg($image, $this->publicPath.'/images/category/'.$name, 95);
        imagedestroy($image);
    }

    public function test_command_creates_small_webp_copies_and_keeps_originals(): void
    {
        $this->makePhoto('big.jpg', 2400, 1800);

        $this->artisan('media:variants', ['--folder' => Media::CATEGORIES])->assertSuccessful();

        $copy = ImageVariants::path(Media::CATEGORIES, 'big.jpg', 240);
        $this->assertFileExists($copy);
        $this->assertFileExists($this->publicPath.'/images/category/big.jpg');
        $this->assertSame(240, getimagesize($copy)[0]);
        $this->assertSame(IMAGETYPE_WEBP, getimagesize($copy)[2]);
    }

    public function test_pages_use_the_copy_and_fall_back_to_the_original(): void
    {
        $this->makePhoto('with-copy.jpg', 1000, 800);
        $this->makePhoto('no-copy.jpg', 1000, 800);
        ImageVariants::generate(Media::CATEGORIES, 'with-copy.jpg');

        $withCopy = new Category(['name' => 'A', 'image' => 'with-copy.jpg']);
        $noCopy = new Category(['name' => 'B', 'image' => 'no-copy.jpg']);

        $this->assertStringEndsWith('/images/category/w240/with-copy.jpg.webp', $withCopy->imageSized(240));
        $this->assertStringEndsWith('/images/category/no-copy.jpg', $noCopy->imageSized(240));
    }
}

<?php

namespace Tests\Feature\Admin;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\Concerns\CreatesShopData;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use CreatesShopData, RefreshDatabase;

    private string $publicPath;

    protected function setUp(): void
    {
        parent::setUp();

        // Uploads go to a throwaway public/ so tests never touch the real images.
        $this->publicPath = sys_get_temp_dir().'/sofar-test-public-'.uniqid();
        File::makeDirectory($this->publicPath);
        $this->app->usePublicPath($this->publicPath);

        $this->createExchangeRate(1500);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->publicPath);

        parent::tearDown();
    }

    public function test_admin_can_create_a_product_with_resized_images(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createProduct()->category;

        $this->actingAs($admin, 'admin')
            ->post(route('product.store'), [
                'name' => 'Hybrid Inverter 5kW',
                'category_id' => $category->id,
                'description' => 'Pure sine wave',
                'price' => 300000,
                'sale_price' => 270000,
                'image' => UploadedFile::fake()->image('main.jpg', 1200, 900),
                'images' => [
                    UploadedFile::fake()->image('a.png', 800, 600),
                    UploadedFile::fake()->image('b.jpg', 640, 480),
                ],
                'specification' => UploadedFile::fake()->create('spec.pdf', 100, 'application/pdf'),
            ])
            ->assertRedirect()
            ->assertSessionHas('alert', 'success');

        $product = Product::where('name', 'Hybrid Inverter 5kW')->firstOrFail();

        $this->assertEquals($admin->id, $product->admin_id);
        $this->assertEquals(180, $product->exchange_rate);
        $this->assertEquals(10, $product->percentage);

        $mainImage = $this->publicPath.'/images/products/'.$product->image;
        $this->assertFileExists($mainImage);
        $this->assertSame([417, 287], array_slice(getimagesize($mainImage), 0, 2));

        $gallery = json_decode($product->gallery, true);
        $this->assertCount(2, $gallery);
        foreach ($gallery as $file) {
            $this->assertSame([417, 287], array_slice(getimagesize($this->publicPath.'/images/products/'.$file), 0, 2));
        }

        $this->assertFileExists($this->publicPath.'/images/pdf/'.$product->specification);
    }

    public function test_invalid_product_flashes_the_admin_alert(): void
    {
        $this->actingAs($this->createAdmin(), 'admin')
            ->from(route('product.create'))
            ->post(route('product.store'), ['name' => ''])
            ->assertRedirect(route('product.create'))
            ->assertSessionHasErrors(['name', 'image'])
            ->assertSessionDoesntHaveErrors(['price']) // empty price = "Price on request"
            ->assertSessionHas('alert', 'error');
    }

    public function test_non_images_are_rejected(): void
    {
        $category = $this->createProduct()->category;

        $this->actingAs($this->createAdmin(), 'admin')
            ->post(route('product.store'), [
                'name' => 'Bad upload',
                'category_id' => $category->id,
                'description' => 'x',
                'price' => 100,
                'sale_price' => 90,
                'image' => UploadedFile::fake()->create('evil.php', 1, 'text/x-php'),
                'images' => [UploadedFile::fake()->image('ok.jpg')],
            ])
            ->assertSessionHasErrors('image');

        $this->assertDatabaseMissing('products', ['name' => 'Bad upload']);
    }

    public function test_admin_can_update_price_and_keep_existing_images(): void
    {
        $product = $this->createProduct(['image' => 'keep.jpg', 'gallery' => '["g1.jpg"]']);

        $this->actingAs($this->createAdmin(), 'admin')
            ->put(route('product.update', encrypt($product->id)), [
                'price' => 200,
                'sale_price' => 150,
            ])
            ->assertSessionHas('alert', 'success');

        $product->refresh();
        $this->assertEquals(150, $product->sale_price);
        $this->assertEquals(0.1, $product->exchange_rate);
        $this->assertSame('keep.jpg', $product->image);
        $this->assertSame('["g1.jpg"]', $product->gallery);
    }

    public function test_admin_can_clear_the_price_for_price_on_request(): void
    {
        $product = $this->createProduct();

        $this->actingAs($this->createAdmin(), 'admin')
            ->put(route('product.update', encrypt($product->id)), ['price' => '', 'sale_price' => ''])
            ->assertSessionHas('alert', 'success');

        $product->refresh();
        $this->assertFalse($product->hasPrice());
        $this->assertEquals(0, $product->percentage);
    }

    public function test_admin_can_deactivate_and_activate_a_product(): void
    {
        $product = $this->createProduct();
        $admin = $this->createAdmin();

        $this->actingAs($admin, 'admin')
            ->post(route('product.status', encrypt($product->id)), ['is_active' => 0]);
        $this->assertFalse($product->fresh()->is_active);
        $this->assertEquals(1, $product->fresh()->status); // legacy column kept in step

        $this->actingAs($admin, 'admin')
            ->post(route('product.status', encrypt($product->id)), ['is_active' => 1]);
        $this->assertTrue($product->fresh()->is_active);
    }

    public function test_admin_can_deactivate_a_category(): void
    {
        $category = $this->createProduct()->category;

        $this->actingAs($this->createAdmin(), 'admin')
            ->post(route('category.status', encrypt($category->id)), ['is_active' => 0]);

        $this->assertFalse($category->fresh()->is_active);
    }

    public function test_public_cannot_create_products(): void
    {
        $this->post('/products', ['name' => 'x'])->assertNotFound();
        $this->post(route('product.store'), ['name' => 'x'])->assertRedirect(route('admin-login'));
    }
}

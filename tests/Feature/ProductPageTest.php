<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesShopData;
use Tests\TestCase;

class ProductPageTest extends TestCase
{
    use CreatesShopData, RefreshDatabase;

    public function test_product_page_is_reached_by_slug(): void
    {
        $product = $this->createProduct(['name' => '10kW Hybrid Inverter']);

        $this->assertSame('10kw-hybrid-inverter', $product->slug);
        $this->assertStringEndsWith('/product/10kw-hybrid-inverter', route('product-details', $product));

        $this->get('/product/10kw-hybrid-inverter')->assertOk()->assertSee('10kW Hybrid Inverter');
    }

    public function test_old_encrypted_links_redirect_permanently(): void
    {
        $product = $this->createProduct();

        $this->get('/products/details/'.encrypt($product->id))
            ->assertStatus(301)
            ->assertRedirect(route('product-details', $product));
    }

    public function test_unknown_slug_and_bad_legacy_id_return_404(): void
    {
        $this->get('/product/does-not-exist')->assertNotFound();
        $this->get('/products/details/garbage')->assertNotFound();
    }

    public function test_duplicate_names_get_unique_slugs_and_renames_keep_the_slug(): void
    {
        $first = $this->createProduct(['name' => 'Solar Street Light 40w']);
        $second = Product::create($first->only('category_id', 'sub_category_id', 'price', 'sale_price') + ['name' => 'solar street light 40w']);

        $this->assertSame('solar-street-light-40w', $first->slug);
        $this->assertSame('solar-street-light-40w-2', $second->slug);

        $first->update(['name' => 'Solar Street Light 40w (new)']);
        $this->assertSame('solar-street-light-40w', $first->fresh()->slug);
    }
}

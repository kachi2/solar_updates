<?php

namespace Tests\Feature;

use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesShopData;
use Tests\TestCase;

/**
 * Ported from the old app's CartMigrationTest.
 */
class CartTest extends TestCase
{
    use CreatesShopData, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cart::clear();
    }

    public function test_cart_add_update_totals_and_remove(): void
    {
        Cart::add(37, 'Sample product', 125.5, 2);

        $this->assertSame(2, Cart::getTotalQuantity());
        $this->assertEquals(251.0, Cart::getSubTotal(false));
        $this->assertSame('251.00', Cart::getSubTotal());

        Cart::update(37, ['quantity' => ['relative' => false, 'value' => 3]]);

        $this->assertSame(3, Cart::getTotalQuantity());
        $this->assertEquals(376.5, Cart::getSubTotal(false));
        $this->assertSame('376.50', Cart::getTotal());

        Cart::remove(37);
        $this->assertTrue(Cart::isEmpty());

        Cart::add(38, 'Another product', 50, 1);
        Cart::clear();
        $this->assertTrue(Cart::isEmpty());
    }

    public function test_totals_use_thousands_separator(): void
    {
        Cart::add(1, 'Inverter', 123456.5, 1);

        $this->assertSame('123,456.50', Cart::getSubTotal());
        $this->assertEquals(123456.5, Cart::getSubTotal(false));
    }

    public function test_add_endpoint_returns_the_updated_basket_summary(): void
    {
        $product = $this->createProduct();

        $this->getJson(route('carts.add', ['id' => $product->id, 'qty' => 2]))
            ->assertOk()
            ->assertJsonPath('cartQuantity', 2)
            ->assertJsonPath('subtotal', 251)
            ->assertJsonPath('item.name', 'Sample solar panel')
            ->assertJsonPath('item.image', 'sample-panel.jpg')
            ->assertJsonPath('item.quantity', 2)
            ->assertJsonPath('item.total', 251);
    }

    public function test_add_endpoint_rejects_invalid_quantity(): void
    {
        $product = $this->createProduct();

        $this->getJson(route('carts.add', ['id' => $product->id, 'qty' => 0]))->assertUnprocessable();
        $this->getJson(route('carts.add', ['id' => 999999, 'qty' => 1]))->assertNotFound();
    }
}

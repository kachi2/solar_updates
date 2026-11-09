<?php

namespace Tests\Feature;

use App\Models\AdminNotify;
use App\Models\Order;
use App\Models\OrderItem;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesShopData;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use CreatesShopData, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cart::clear();
    }

    private function fillCart(): void
    {
        $product = $this->createProduct();
        Cart::add($product->id, $product->name, $product->sale_price, 2, ['image' => $product->image], [], $product::class);
    }

    public function test_guests_must_log_in_to_check_out(): void
    {
        $this->get(route('checkout.index'))->assertRedirect(route('login'));
    }

    public function test_empty_cart_redirects_home(): void
    {
        $this->actingAs($this->createCustomer())
            ->get(route('checkout.index'))
            ->assertRedirect(route('index'));
    }

    public function test_checkout_page_snapshots_the_cart_into_order_items(): void
    {
        $user = $this->createCustomer();
        $this->createDefaultAddress($user);
        $this->fillCart();

        $this->actingAs($user)->get(route('checkout.index'))->assertOk();

        $item = OrderItem::where('user_id', $user->id)->firstOrFail();
        $this->assertEquals(2, $item->qty);
        $this->assertEquals(251, $item->payable);
    }

    public function test_checkout_page_shows_address_and_the_amount_actually_charged(): void
    {
        $user = $this->createCustomer();
        $this->createDefaultAddress($user, ['receiver_name' => 'Ada Obi', 'receiver_phone' => '8031112222', 'city' => 'Ikeja', 'state' => 'Lagos']);
        $this->fillCart(); // 2 × ₦125.50

        $this->actingAs($user)->get(route('checkout.index'))
            ->assertOk()
            ->assertSee('Ada Obi')
            ->assertSee('8031112222')
            ->assertSee('Ikeja, Lagos')
            ->assertDontSee('₦800')
            ->assertSee('₦251')
            ->assertSee('name="payment_method" value="card"', false)
            ->assertDontSee('Add a delivery address to place your order.');
    }

    public function test_place_order_is_disabled_without_an_address(): void
    {
        $user = $this->createCustomer();
        $this->fillCart();

        $this->actingAs($user)->get(route('checkout.index'))
            ->assertOk()
            ->assertSee("You haven't added a delivery address yet.", false)
            ->assertSee('Add a delivery address to place your order.');
    }

    public function test_manual_payment_places_the_order_and_clears_the_cart(): void
    {
        $user = $this->createCustomer();
        $shipping = $this->createDefaultAddress($user);
        $this->fillCart();
        $this->actingAs($user)->get(route('checkout.index'));

        $this->actingAs($user)
            ->post(route('checkout.store'), ['payment_method' => 'cash_delivery'])
            ->assertOk()
            ->assertViewIs('users.products.orders')
            ->assertSee('Thank you, your order has been placed.')
            ->assertSee('2 × ₦126') // 2 × ₦125.50, shown rounded
            ->assertSee('₦251')
            ->assertDontSee('₦0')
            ->assertSee('Awaiting payment')
            ->assertSee('Processing')
            ->assertSee('Pay on delivery', false);

        $order = Order::where('user_id', $user->id)->firstOrFail();
        $this->assertEquals(251, $order->amount);
        $this->assertEquals($shipping->id, $order->shipping_id);
        $this->assertEquals(0, $order->is_paid);
        $this->assertTrue(Cart::isEmpty());
        $this->assertTrue(AdminNotify::where('message', 'like', '%'.$order->order_No.'%')->exists());
    }

    public function test_card_payment_shows_the_flutterwave_page_with_the_public_key(): void
    {
        config(['services.flutterwave.public_key' => 'FLWPUBK_TEST-abc']);
        $user = $this->createCustomer();
        $this->createDefaultAddress($user, ['receiver_name' => 'Ada Obi', 'receiver_phone' => '8031112222']);
        $this->fillCart();
        $this->actingAs($user)->get(route('checkout.index'));

        $response = $this->actingAs($user)
            ->post(route('checkout.store'), ['payment_method' => 'card'])
            ->assertOk()
            ->assertViewIs('users.products.checkouts')
            ->assertSee('FLWPUBK_TEST-abc', false)
            ->assertSee('Ada Obi')
            ->assertSee('8031112222')
            ->assertSee('Pay ₦251')
            ->assertSee('amount: 251,', false);

        $order = Order::where('user_id', $user->id)->firstOrFail();
        $response->assertSee('Order #'.$order->order_No);

        $this->assertFalse(Cart::isEmpty(), 'Cart is kept until Flutterwave confirms payment');
    }

    public function test_order_needs_a_payment_method_and_default_address(): void
    {
        $user = $this->createCustomer();
        $this->fillCart();
        $this->actingAs($user)->get(route('checkout.index'));

        $this->actingAs($user)->post(route('checkout.store'), [])
            ->assertRedirect(route('checkout.index'))->assertSessionHasErrors();

        $this->actingAs($user)->post(route('checkout.store'), ['payment_method' => 'card'])
            ->assertRedirect(route('checkout.index'))->assertSessionHasErrors();

        $this->assertDatabaseCount('orders', 0);
    }
}

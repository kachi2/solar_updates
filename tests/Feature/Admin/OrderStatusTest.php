<?php

namespace Tests\Feature\Admin;

use App\Mail\DispatchedMail;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\Concerns\CreatesShopData;
use Tests\TestCase;

class OrderStatusTest extends TestCase
{
    use CreatesShopData, RefreshDatabase;

    public function test_dispatching_an_order_emails_the_customer_once(): void
    {
        Mail::fake();
        $admin = $this->createAdmin();
        $order = $this->createUnpaidOrder($customer = $this->createCustomer());
        $url = route('order.status.update', encrypt($order->order_No));

        $this->actingAs($admin, 'admin')
            ->post($url, ['delivery' => 0, 'dispatch' => 1, 'payment' => 1])
            ->assertSessionHas('alert', 'success');

        $order->refresh();
        $this->assertEquals(1, $order->dispatch_status);
        $this->assertEquals(1, $order->is_paid);
        $this->assertStringStartsWith('SFSL', $order->payment_ref);

        Mail::assertSent(DispatchedMail::class, fn (DispatchedMail $mail) => $mail->hasTo($customer->email)
            && $mail->data['order_No'] === $order->order_No);

        // Saving again while already dispatched must not email again.
        $this->actingAs($admin, 'admin')->post($url, ['delivery' => 1, 'dispatch' => 1, 'payment' => 1]);
        Mail::assertSentCount(1);
    }

    public function test_status_values_are_validated(): void
    {
        $order = $this->createUnpaidOrder($this->createCustomer());

        $this->actingAs($this->createAdmin(), 'admin')
            ->post(route('order.status.update', encrypt($order->order_No)), ['dispatch' => 'yes'])
            ->assertSessionHasErrors(['delivery', 'dispatch', 'payment']);
    }

    public function test_admin_pages_survive_orders_from_deleted_customers(): void
    {
        $customer = $this->createCustomer(['email' => 'gone@example.com']);
        $order = $this->createUnpaidOrder($customer);
        Transaction::create(['user_id' => $customer->id, 'payment_ref' => 'SFSL1', 'amount' => 251]);
        $customer->delete();

        $admin = $this->createAdmin();

        foreach (['/admin', '/admin/orders', '/admin/transactions'] as $url) {
            $this->actingAs($admin, 'admin')->get($url)
                ->assertOk()
                ->assertSee('Deleted customer');
        }

        $this->actingAs($admin, 'admin')->get('/admin')->assertSee((string) $order->order_No);
    }

    public function test_dashboard_shows_the_real_payment_method(): void
    {
        $order = $this->createUnpaidOrder($this->createCustomer());
        $order->update(['payment_method' => 'cash_delivery']);

        $this->actingAs($this->createAdmin(), 'admin')->get('/admin')
            ->assertOk()
            ->assertSee('Cash on Delivery');
    }

    public function test_menu_added_in_admin_gets_a_slug_and_site_keeps_working(): void
    {
        $this->actingAs($this->createAdmin(), 'admin')
            ->post(route('admin.menu.store'), ['name' => 'Solar Guides'])
            ->assertSessionHas('alert', 'success');

        $this->assertDatabaseHas('menus', ['name' => 'Solar Guides', 'slug' => 'solar-guides']);

        // A legacy row with no slug must not break pages that list the menu.
        DB::table('menus')->insert(['name' => 'Broken']);

        $this->get('/')->assertOk()->assertSee('/pages/solar-guides', false)->assertDontSee('>Broken<', false);
    }

    public function test_tampered_ids_return_404(): void
    {
        $this->actingAs($this->createAdmin(), 'admin')
            ->get(route('order.status', 'not-a-valid-encrypted-id'))
            ->assertNotFound();
    }
}

<?php

namespace Tests\Feature\Admin;

use App\Mail\DispatchedMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function test_tampered_ids_return_404(): void
    {
        $this->actingAs($this->createAdmin(), 'admin')
            ->get(route('order.status', 'not-a-valid-encrypted-id'))
            ->assertNotFound();
    }
}

<?php

namespace Tests\Feature;

use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\Concerns\CreatesShopData;
use Tests\TestCase;

class PaymentVerificationTest extends TestCase
{
    use CreatesShopData, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.flutterwave.secret_key' => 'FLWSECK_TEST-secret']);
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function fakeFlutterwave(array $overrides = [], string $apiStatus = 'success'): void
    {
        Http::fake([
            'api.flutterwave.com/v3/transactions/*' => Http::response([
                'status' => $apiStatus,
                'data' => $overrides + [
                    'status' => 'successful',
                    'amount' => 251,
                    'currency' => 'NGN',
                    'tx_ref' => 'SFSL123456',
                    'flw_ref' => 'FLW-MOCK-REF',
                ],
            ]),
        ]);
    }

    public function test_successful_payment_marks_the_order_paid(): void
    {
        $this->fakeFlutterwave();
        $user = $this->createCustomer();
        $order = $this->createUnpaidOrder($user, 251);

        $this->actingAs($user)
            ->get(route('confirm.payment', '987654'))
            ->assertOk()
            ->assertViewIs('users.products.completed');

        $order->refresh();
        $this->assertEquals(1, $order->is_paid);
        $this->assertSame('SFSL123456', $order->payment_ref);
        $this->assertSame('FLW-MOCK-REF', $order->external_ref);
        $this->assertDatabaseHas('transactions', ['user_id' => $user->id, 'order_No' => $order->order_No, 'external_ref' => 'FLW-MOCK-REF']);

        Http::assertSent(fn ($request) => $request->hasHeader('Authorization', 'Bearer FLWSECK_TEST-secret')
            && str_ends_with($request->url(), '/transactions/987654/verify'));
    }

    public function test_failed_card_payment_is_not_marked_paid(): void
    {
        // The old app only checked the API call status, so this case was marked paid.
        $this->fakeFlutterwave(['status' => 'failed']);
        $user = $this->createCustomer();
        $order = $this->createUnpaidOrder($user);

        $this->actingAs($user)
            ->get(route('confirm.payment', '987654'))
            ->assertRedirect(route('checkout.index'))
            ->assertSessionHasErrors();

        $this->assertEquals(0, $order->fresh()->is_paid);
        $this->assertDatabaseCount('transactions', 0);
    }

    public function test_underpayment_is_rejected(): void
    {
        $this->fakeFlutterwave(['amount' => 100]);
        $user = $this->createCustomer();
        $order = $this->createUnpaidOrder($user, 251);

        $this->actingAs($user)->get(route('confirm.payment', '987654'))->assertRedirect(route('checkout.index'));

        $this->assertEquals(0, $order->fresh()->is_paid);
    }

    public function test_wrong_currency_is_rejected(): void
    {
        $this->fakeFlutterwave(['currency' => 'USD']);
        $user = $this->createCustomer();
        $order = $this->createUnpaidOrder($user, 251);

        $this->actingAs($user)->get(route('confirm.payment', '987654'))->assertRedirect(route('checkout.index'));

        $this->assertEquals(0, $order->fresh()->is_paid);
    }

    public function test_unknown_transaction_is_rejected(): void
    {
        Http::fake(['api.flutterwave.com/*' => Http::response(['status' => 'error', 'message' => 'No transaction was found'], 400)]);
        $user = $this->createCustomer();
        $order = $this->createUnpaidOrder($user);

        $this->actingAs($user)->get(route('confirm.payment', '1'))->assertRedirect(route('checkout.index'));

        $this->assertEquals(0, $order->fresh()->is_paid);
    }

    public function test_the_same_payment_cannot_be_applied_twice(): void
    {
        $this->fakeFlutterwave();
        $user = $this->createCustomer();
        $this->createUnpaidOrder($user, 251);
        $this->actingAs($user)->get(route('confirm.payment', '987654'));

        $second = $this->createUnpaidOrder($user, 251);
        $this->actingAs($user)
            ->get(route('confirm.payment', '987654'))
            ->assertRedirect(route('users.orders'));

        $this->assertEquals(0, $second->fresh()->is_paid);
        $this->assertSame(1, Transaction::count());
    }
}

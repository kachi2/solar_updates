<?php

namespace Tests\Unit;

use App\Enums\DispatchStatus;
use App\Enums\OrderProgress;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\TransactionType;
use App\Enums\Visibility;
use PHPUnit\Framework\TestCase;

class OrderEnumsTest extends TestCase
{
    public function test_legacy_values_map_to_cases_without_throwing(): void
    {
        $this->assertSame(PaymentStatus::Pending, PaymentStatus::fromLegacy(null));
        $this->assertSame(PaymentStatus::Paid, PaymentStatus::fromLegacy('1'));
        $this->assertSame(DispatchStatus::Pending, DispatchStatus::fromLegacy(null));
        $this->assertSame(OrderStatus::Cancelled, OrderStatus::fromLegacy(3));
        $this->assertSame(OrderStatus::Pending, OrderStatus::fromLegacy(99));
        $this->assertSame(TransactionType::Payment, TransactionType::fromLegacy('payment')); // older value
        $this->assertSame(TransactionType::Credit, TransactionType::fromLegacy('credit'));
        $this->assertSame(Visibility::Visible, Visibility::fromLegacy(null));
        $this->assertSame(Visibility::Hidden, Visibility::fromLegacy(1));
    }

    /**
     * @return array<string, array{OrderStatus, DispatchStatus, OrderProgress}>
     */
    public static function progressCases(): array
    {
        return [
            'new order' => [OrderStatus::Pending, DispatchStatus::Pending, OrderProgress::Processing],
            'initiated is not delivered' => [OrderStatus::Initiated, DispatchStatus::Pending, OrderProgress::Processing],
            'dispatched' => [OrderStatus::Initiated, DispatchStatus::Dispatched, OrderProgress::Dispatched],
            'delivered' => [OrderStatus::Initiated, DispatchStatus::Delivered, OrderProgress::Delivered],
            'completed' => [OrderStatus::Completed, DispatchStatus::Pending, OrderProgress::Completed],
            'cancelled wins' => [OrderStatus::Cancelled, DispatchStatus::Delivered, OrderProgress::Cancelled],
        ];
    }

    /**
     * @dataProvider progressCases
     */
    public function test_customer_facing_progress(OrderStatus $status, DispatchStatus $dispatch, OrderProgress $expected): void
    {
        $this->assertSame($expected, OrderProgress::for($status, $dispatch));
    }

    public function test_payment_method_labels(): void
    {
        $this->assertSame('Pay on delivery', PaymentMethod::labelFor('cash_delivery'));
        $this->assertSame('Card (Flutterwave)', PaymentMethod::labelFor('card'));
        $this->assertSame('Bank Transfer', PaymentMethod::labelFor('bank_transfer'));
        $this->assertSame('—', PaymentMethod::labelFor(null));
    }
}

<?php

namespace App\Enums;

use Illuminate\Support\Str;

/**
 * orders.payment_method / transactions.payment_method
 */
enum PaymentMethod: string
{
    case Card = 'card';
    case CashOnDelivery = 'cash_delivery';

    public function label(): string
    {
        return match ($this) {
            self::Card => 'Card (Flutterwave)',
            self::CashOnDelivery => 'Pay on delivery',
        };
    }

    /**
     * Label for any stored value, including unknown legacy ones.
     */
    public static function labelFor(?string $value): string
    {
        return self::tryFrom((string) $value)?->label()
            ?? ($value ? Str::headline($value) : '—');
    }
}

<?php

namespace App\Enums;

use App\Enums\Concerns\HasLegacyValues;

/**
 * transactions.type ("payment" was also used by older code).
 */
enum TransactionType: string
{
    use HasLegacyValues;

    case Payment = 'payment for';
    case Credit = 'credit';

    public static function fallback(): self
    {
        return self::Payment;
    }

    public function label(): string
    {
        return match ($this) {
            self::Payment => 'Card payment',
            self::Credit => 'Account credit',
        };
    }
}

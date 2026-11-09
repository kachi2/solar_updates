<?php

namespace App\Enums;

use App\Enums\Concerns\HasLegacyValues;

/**
 * orders.is_paid
 */
enum PaymentStatus: int
{
    use HasLegacyValues;

    case Pending = 0;
    case Paid = 1;

    public static function fallback(): self
    {
        return self::Pending;
    }

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Awaiting payment',
            self::Paid => 'Paid',
        };
    }

    /**
     * Status pill style (CSS modifier of .sofar-status).
     */
    public function tone(): string
    {
        return $this === self::Paid ? 'ok' : 'wait';
    }
}

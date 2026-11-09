<?php

namespace App\Enums;

use App\Enums\Concerns\HasLegacyValues;

/**
 * orders.dispatch_status (NULL on older orders = pending).
 */
enum DispatchStatus: int
{
    use HasLegacyValues;

    case Pending = 0;
    case Dispatched = 1;
    case Delivered = 2;

    public static function fallback(): self
    {
        return self::Pending;
    }

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Dispatched => 'Dispatched',
            self::Delivered => 'Delivered',
        };
    }
}

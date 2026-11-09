<?php

namespace App\Enums;

use App\Enums\Concerns\HasLegacyValues;

/**
 * orders.is_delivered. Despite the column name this is the order's overall
 * status, as set in the admin "Update status" form.
 */
enum OrderStatus: int
{
    use HasLegacyValues;

    case Pending = 0;
    case Initiated = 1;
    case Completed = 2;
    case Cancelled = 3;

    public static function fallback(): self
    {
        return self::Pending;
    }

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Initiated => 'Initiated',
            self::Completed => 'Completed',
            self::Cancelled => 'Cancelled',
        };
    }

    /**
     * Completed and cancelled orders can no longer be changed in the admin.
     */
    public function isFinal(): bool
    {
        return in_array($this, [self::Completed, self::Cancelled], true);
    }
}

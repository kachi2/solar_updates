<?php

namespace App\Enums;

use App\Enums\Concerns\HasLegacyValues;

/**
 * products.status / news.status: 1 = disabled in the admin, anything else (incl. NULL) = visible.
 */
enum Visibility: int
{
    use HasLegacyValues;

    case Visible = 0;
    case Hidden = 1;

    public static function fallback(): self
    {
        return self::Visible;
    }

    public function label(): string
    {
        return $this === self::Hidden ? 'Disabled' : 'Enabled';
    }
}

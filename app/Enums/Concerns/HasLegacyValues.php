<?php

namespace App\Enums\Concerns;

/**
 * Legacy rows hold NULLs, strings like "1", or values the admin never used.
 * fromLegacy() maps them to a case instead of throwing.
 */
trait HasLegacyValues
{
    public static function fromLegacy(mixed $value): self
    {
        if ($value instanceof self) {
            return $value;
        }

        $case = null;

        if ($value !== null && $value !== '') {
            $backing = (new \ReflectionEnum(self::class))->getBackingType()?->getName();
            $case = self::tryFrom($backing === 'int' ? (int) $value : (string) $value);
        }

        return $case ?? self::fallback();
    }

    abstract public static function fallback(): self;
}

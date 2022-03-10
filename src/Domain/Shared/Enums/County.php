<?php

namespace Domain\Shared\Enums;

use Closure;
use Spatie\Enum\Laravel\Enum;

/**
 * @method static self augusta()
 * @method static self franklin()
 */
class County extends Enum
{
    protected static function labels(): Closure
    {
        return function (string $value): string {
            return ucfirst($value);
        };
    }
}

<?php

namespace Domain\Shared\Enums;

use Closure;
use Spatie\Enum\Laravel\Enum;

/**
 * @method static self female()
 * @method static self male()
 */
class Sex extends Enum
{
    protected static function labels(): Closure
    {
        return function (string $value): string {
            return ucfirst($value);
        };
    }
}

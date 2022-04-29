<?php

namespace Domain\Shared\Enums;

use Closure;
use Spatie\Enum\Laravel\Enum;

/**
 * @method static self black()
 * @method static self colored()
 * @method static self mulatto()
 * @method static self white()
 */
class Race extends Enum
{
    protected static function labels(): Closure
    {
        return function (string $value): string {
            return ucfirst($value);
        };
    }
}

<?php

namespace Domain\Shared\Enums;

use Closure;
use Spatie\Enum\Laravel\Enum;

/**
 * @method static self sunday()
 * @method static self monday()
 * @method static self tuesday()
 * @method static self wednesday()
 * @method static self thursday()
 * @method static self friday()
 * @method static self saturday()
 */
class Weekday extends Enum
{
    protected static function labels(): Closure
    {
        return function (string $value): string {
            return ucfirst($value);
        };
    }
}

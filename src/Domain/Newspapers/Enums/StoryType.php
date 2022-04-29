<?php

namespace Domain\Newspapers\Enums;

use Closure;
use Spatie\Enum\Laravel\Enum;

/**
 * @method static self article()
 * @method static self classified()
 * @method static self editorial()
 * @method static self judicial()
 * @method static self letter()
 * @method static self obituary()
 * @method static self poem()
 * @method static self society()
 */
class StoryType extends Enum
{
    protected static function labels(): Closure
    {
        return function (string $value): string {
            return ucfirst($value);
        };
    }
}

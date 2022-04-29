<?php

namespace Domain\ChurchRecords\Enums;

use Closure;
use Spatie\Enum\Laravel\Enum;

/**
 * @method static self baptism()
 * @method static self communion()
 * @method static self confirmation()
 * @method static self death()
 * @method static self marriage()
 */
class RecordType extends Enum
{
    protected static function labels(): Closure
    {
        return function (string $value): string {
            return ucfirst($value);
        };
    }
}

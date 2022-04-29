<?php

namespace Domain\CivilWarImages\Enums;

use Closure;
use Spatie\Enum\Laravel\Enum;

/**
 * @method static self building()
 * @method static self drawing()
 * @method static self engraving()
 * @method static self facsimile()
 * @method static self lithograph()
 * @method static self photograph()
 * @method static self postcard()
 * @method static self print()
 */
class ImageType extends Enum
{
    protected static function labels(): Closure
    {
        return function (string $value): string {
            return ucfirst($value);
        };
    }
}

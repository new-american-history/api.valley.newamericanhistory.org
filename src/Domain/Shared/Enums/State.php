<?php

namespace Domain\Shared\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self dc()
 * @method static self maryland()
 * @method static self northCarolina()
 * @method static self pennsylvania()
 * @method static self virginia()
 * @method static self westVirginia()
 * @method static self westVirginiaAndVirginia()
 */
class State extends Enum
{
    protected static function labels(): array
    {
        return [
            'dc' => 'Washington, DC',
            'maryland' => 'Maryland',
            'northCarolina' => 'North Carolina',
            'pennsylvania' => 'Pennsylvania',
            'virginia' => 'Virginia',
            'westVirginia' => 'West Virginia',
            'westVirginiaAndVirginia' => 'West Virginia and Virginia',
        ];
    }
}

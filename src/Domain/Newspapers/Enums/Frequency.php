<?php

namespace Domain\Newspapers\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self biWeekly()
 * @method static self semiWeekly()
 * @method static self weekly()
 */
class Frequency extends Enum
{
    protected static function labels(): array
    {
        return [
            'biWeekly' => 'Bi-weekly',
            'semiWeekly' => 'Semi-weekly',
            'weekly' => 'Weekly',
        ];
    }
}

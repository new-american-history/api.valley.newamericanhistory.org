<?php

namespace Domain\CivilWarImages\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self frankLesliesIllustratedWeekly()
 * @method static self harpersWeekly()
 * @method static self illustratedLondonNews()
 * @method static self originalPhotograph()
 * @method static self southernIllustratedNews()
 */
class OriginalSource extends Enum
{
    protected static function labels(): array
    {
        return [
            'frankLesliesIllustratedWeekly' => 'Frank Leslie’s Illustrated Weekly',
            'harpersWeekly' => 'Harper’s Weekly',
            'illustratedLondonNews' => 'Illustrated London News',
            'originalPhotograph' => 'Original photograph',
            'southernIllustratedNews' => 'Southern Illustrated News',
        ];
    }
}

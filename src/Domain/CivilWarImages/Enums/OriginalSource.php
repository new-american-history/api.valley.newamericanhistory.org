<?php

namespace Domain\CivilWarImages\Enums;

enum OriginalSource: string
{
    case FRANK_LESLIES_ILLUSTRATED_WEEKLY = 'frankLesliesIllustratedWeekly';
    case HARPERS_WEEKLY = 'harpersWeekly';
    case ILLUSTRATED_LONDON_NEWS = 'illustratedLondonNews';
    case ORIGINAL_PHOTOGRAPH = 'originalPhotograph';
    case SOUTHERN_ILLUSTRATED_NEWS = 'southernIllustratedNews';

    public function label(): string
    {
        return match($this) {
            self::FRANK_LESLIES_ILLUSTRATED_WEEKLY => "Frank Leslie's Illustrated Weekly",
            self::HARPERS_WEEKLY => "Harper's Weekly",
            self::ILLUSTRATED_LONDON_NEWS => 'Illustrated London News',
            self::ORIGINAL_PHOTOGRAPH => 'Original photograph',
            self::SOUTHERN_ILLUSTRATED_NEWS => 'Southern Illustrated News',
        };
    }

    public static function fromValue(string $value): ?self
    {
        return self::tryFrom($value);
    }
}

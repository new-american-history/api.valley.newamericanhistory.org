<?php

namespace Domain\Shared\Enums;

enum State: string
{
    case DC = 'dc';
    case MARYLAND = 'maryland';
    case NORTH_CAROLINA = 'northCarolina';
    case PENNSYLVANIA = 'pennsylvania';
    case VIRGINIA = 'virginia';
    case WEST_VIRGINIA = 'westVirginia';
    case WEST_VIRGINIA_AND_VIRGINIA = 'westVirginiaAndVirginia';

    public function label(): string
    {
        return match($this) {
            self::DC => 'Washington, DC',
            self::MARYLAND => 'Maryland',
            self::NORTH_CAROLINA => 'North Carolina',
            self::PENNSYLVANIA => 'Pennsylvania',
            self::VIRGINIA => 'Virginia',
            self::WEST_VIRGINIA => 'West Virginia',
            self::WEST_VIRGINIA_AND_VIRGINIA => 'West Virginia and Virginia',
        };
    }

    public static function fromValue(string $value): ?self
    {
        return self::tryFrom($value);
    }
}

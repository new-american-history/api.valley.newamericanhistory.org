<?php

namespace Domain\Shared\Enums;

enum Weekday: string
{
    case SUNDAY = 'sunday';
    case MONDAY = 'monday';
    case TUESDAY = 'tuesday';
    case WEDNESDAY = 'wednesday';
    case THURSDAY = 'thursday';
    case FRIDAY = 'friday';
    case SATURDAY = 'saturday';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public static function fromValue(string $value): ?self
    {
        return self::tryFrom($value);
    }
}

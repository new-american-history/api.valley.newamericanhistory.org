<?php

namespace Domain\ChurchRecords\Enums;

enum RecordType: string
{
    case BAPTISM = 'baptism';
    case COMMUNION = 'communion';
    case CONFIRMATION = 'confirmation';
    case DEATH = 'death';
    case MARRIAGE = 'marriage';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public static function fromValue(string $value): ?self
    {
        return self::tryFrom($value);
    }
}

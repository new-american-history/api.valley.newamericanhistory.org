<?php

namespace Domain\Shared\Enums;

enum Race: string
{
    case BLACK = 'black';
    case COLORED = 'colored';
    case MULATTO = 'mulatto';
    case WHITE = 'white';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public static function fromValue(string $value): ?self
    {
        return self::tryFrom($value);
    }
}

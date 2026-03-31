<?php

namespace Domain\Shared\Enums;

enum Sex: string
{
    case FEMALE = 'female';
    case MALE = 'male';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public static function fromValue(string $value): ?self
    {
        return self::tryFrom($value);
    }
}

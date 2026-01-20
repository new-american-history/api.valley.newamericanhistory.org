<?php

namespace Domain\Shared\Enums;

enum County: string
{
    case AUGUSTA = 'augusta';
    case FRANKLIN = 'franklin';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public static function fromValue(string $value): ?self
    {
        return self::tryFrom($value);
    }
}

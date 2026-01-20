<?php

namespace Domain\Shared\Enums;

enum Chapter: string
{
    case AFTERMATH = 'aftermath';
    case EVE = 'eve';
    case WAR = 'war';

    public function label(): string
    {
        return match($this) {
            self::AFTERMATH => 'The Aftermath',
            self::EVE => 'The Eve of War',
            self::WAR => 'The War Years',
        };
    }

    public static function fromValue(string $value): ?self
    {
        return self::tryFrom($value);
    }
}

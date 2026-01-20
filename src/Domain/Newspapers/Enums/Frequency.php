<?php

namespace Domain\Newspapers\Enums;

enum Frequency: string
{
    case BI_WEEKLY = 'biWeekly';
    case SEMI_WEEKLY = 'semiWeekly';
    case WEEKLY = 'weekly';

    public function label(): string
    {
        return match($this) {
            self::BI_WEEKLY => 'Bi-weekly',
            self::SEMI_WEEKLY => 'Semi-weekly',
            self::WEEKLY => 'Weekly',
        };
    }

    public function fromValue(string $value): ?self
    {
        return self::tryFrom($value);
    }
}

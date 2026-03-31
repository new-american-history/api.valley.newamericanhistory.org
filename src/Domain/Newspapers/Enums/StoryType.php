<?php

namespace Domain\Newspapers\Enums;

enum StoryType: string
{
    case ARTICLE = 'article';
    case CLASSIFIED = 'classified';
    case EDITORIAL = 'editorial';
    case JUDICIAL = 'judicial';
    case LETTER = 'letter';
    case OBITUARY = 'obituary';
    case POEM = 'poem';
    case SOCIETY = 'society';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public static function fromValue(string $value): ?self
    {
        return self::tryFrom($value);
    }
}

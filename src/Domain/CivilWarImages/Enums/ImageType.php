<?php

namespace Domain\CivilWarImages\Enums;

enum ImageType: string
{
    case BUILDING = 'building';
    case DRAWING = 'drawing';
    case ENGRAVING = 'engraving';
    case FACSIMILE = 'facsimile';
    case LITHOGRAPH = 'lithograph';
    case PHOTOGRAPH = 'photograph';
    case POSTCARD = 'postcard';
    case PRINT = 'print';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public static function fromValue(string $value): ?self
    {
        return self::tryFrom($value);
    }
}

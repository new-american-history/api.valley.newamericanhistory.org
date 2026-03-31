<?php

namespace Domain\Shared\Traits;

use Domain\Shared\Enums\County;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasCountyEnum
{
    protected function county(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value instanceof County ? $value : County::tryFrom($value),
            set: fn ($value) => $value instanceof County ? $value->value : $value,
        );
    }

    protected function countyLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->county?->label(),
        );
    }
}

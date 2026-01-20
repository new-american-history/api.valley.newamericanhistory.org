<?php

namespace Domain\Shared\Traits;

use Domain\Shared\Enums\County;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasCountyEnum
{
    protected function countyLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->county?->label(),
        );
    }

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'county' => County::class,
        ]);
    }
}

<?php

namespace Domain\Shared\Traits;

use Domain\Shared\Enums\County;

trait HasCountyEnum
{
    protected function getCountyLabelAttribute(): ?string
    {
        $enum = County::tryFrom($this->county);
        return $enum->label ?? null;
    }
}

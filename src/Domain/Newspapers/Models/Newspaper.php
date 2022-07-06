<?php

namespace Domain\Newspapers\Models;

use Domain\Shared\Enums\State;
use Domain\Newspapers\Models\Edition;
use Domain\Shared\Traits\HasCountyEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Newspaper extends Model
{
    use HasCountyEnum;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['county_label', 'state_label'];

    public function editions(): HasMany
    {
        return $this->hasMany(Edition::class)
            ->orderBy('date');
    }

    public static $exactFilters = [
        'county',
        'state',
    ];

    public static $fuzzyFilters = [
        'city',
        'name',
    ];

    protected function getStateLabelAttribute(): ?string
    {
        $enum = State::tryFrom($this->state);
        return $enum->label ?? null;
    }
}

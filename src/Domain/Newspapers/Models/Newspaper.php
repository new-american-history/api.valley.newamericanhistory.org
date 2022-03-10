<?php

namespace Domain\Newspapers\Models;

use Domain\Shared\Enums\State;
use Domain\Newspapers\Models\Edition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Newspaper extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['state_label'];

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
        'name',
        'city',
    ];

    protected function getStateLabelAttribute(): ?string
    {
        $enum = State::tryFrom($this->state);
        return $enum->label ?? null;
    }
}

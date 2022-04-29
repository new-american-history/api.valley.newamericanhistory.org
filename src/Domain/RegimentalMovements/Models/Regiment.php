<?php

namespace Domain\RegimentalMovements\Models;

use Domain\Shared\Enums\State;
use Domain\Shared\Traits\HasCountyEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Domain\RegimentalMovements\Models\RegimentalMovement;

class Regiment extends Model
{
    use HasCountyEnum;

    protected $guarded = [];

    protected $appends = ['county_label', 'state_label'];

    public $timestamps = false;

    public function movements(): HasMany
    {
        return $this->hasMany(RegimentalMovement::class, 'regiment_id')
            ->orderBy('battle_start_date');
    }

    protected function getStateLabelAttribute(): ?string
    {
        $enum = State::tryFrom($this->state);
        return $enum->label ?? null;
    }
}

<?php

namespace Domain\RegimentalMovements\Models;

use Illuminate\Database\Eloquent\Model;
use Domain\RegimentalMovements\Enums\State;
use Domain\RegimentalMovements\Models\Regiment;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegimentalMovement extends Model
{
    protected $guarded = [];

    protected $appends = ['battle_state_label'];

    protected $dates = [
        'battle_start_date',
        'battle_end_date',
    ];

    protected $casts = [
        'regiment_id' => 'integer',
    ];

    public function regiment(): BelongsTo
    {
        return $this->belongsTo(Regiment::class);
    }

    public static $exactFilters = [
        'battle_state',
        'state',
        'battle_start_date',
        'battle_end_date',
        'regiment_id',
        'killed',
        'wounded',
        'missing',

        'regiment.county',
        'regiment.state',
    ];

    public static $fuzzyFilters = [
        'battle_name',
        'summary',
        'commander',
        'corps',
        'division',
        'brigade',
        'regiment_strength',
        'local_weather',
        'georgetown_weather',

        'regiment.name',
        'regiment.name_in_dossiers',
    ];

    protected function getBattleStateLabelAttribute(): ?string
    {
        $enum = State::tryFrom($this->battle_state);
        return $enum->label ?? null;
    }
}

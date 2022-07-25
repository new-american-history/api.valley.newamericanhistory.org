<?php

namespace Domain\RegimentalMovements\Models;

use Domain\Shared\Enums\State;
use Illuminate\Database\Eloquent\Model;
use Domain\RegimentalMovements\Models\Regiment;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegimentalMovement extends Model
{
    protected $guarded = [];

    protected $appends = ['battle_state_label'];

    protected $casts = [
        'regiment_id' => 'integer',
    ];

    public function regiment(): BelongsTo
    {
        return $this->belongsTo(Regiment::class);
    }

    public static $exactFilters = [
        'battle_state',
        'brigade',
        'corps',
        'division',
        'killed',
        'missing',
        'regiment_id',
        'state',
        'wounded',

        'regiment.county',
        'regiment.name',
        'regiment.state',
    ];

    public static $exactFiltersWithCommas = [
        'battle_name',
    ];

    public static $fuzzyFilters = [
        'commander',
        'georgetown_weather',
        'local_weather',
        'regiment_strength',
        'summary',

        'regiment.name_in_dossiers',
    ];

    public static $dateFilters = [
        'battle_end_date',
        'battle_start_date',
    ];

    protected function getBattleStateLabelAttribute(): ?string
    {
        $enum = State::tryFrom($this->battle_state);
        return $enum->label ?? null;
    }
}

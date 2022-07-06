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
        'battle_name',
        'battle_state',
        'state',
        'corps',
        'division',
        'brigade',
        'regiment_id',
        'killed',
        'wounded',
        'missing',

        'regiment.county',
        'regiment.state',
        'regiment.name',
    ];

    public static $fuzzyFilters = [
        'summary',
        'commander',
        'regiment_strength',
        'local_weather',
        'georgetown_weather',

        'regiment.name_in_dossiers',
    ];

    public static $dateFilters = [
        'battle_start_date',
        'battle_end_date',
    ];

    protected function getBattleStateLabelAttribute(): ?string
    {
        $enum = State::tryFrom($this->battle_state);
        return $enum->label ?? null;
    }
}

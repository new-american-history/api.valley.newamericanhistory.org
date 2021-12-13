<?php

namespace Domain\RegimentalMovements\Models;

use Illuminate\Database\Eloquent\Model;
use Domain\RegimentalMovements\Models\Regiment;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegimentalMovement extends Model
{
    protected $guarded = [];

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
}

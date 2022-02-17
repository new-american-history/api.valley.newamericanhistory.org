<?php

namespace Domain\RegimentalMovements\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Domain\RegimentalMovements\Models\RegimentalMovement;

class Regiment extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function movements(): HasMany
    {
        return $this->hasMany(RegimentalMovement::class, 'regiment_id')
            ->orderBy('battle_start_date');
    }
}

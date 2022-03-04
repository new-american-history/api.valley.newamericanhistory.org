<?php

namespace Domain\CohabitationRecords\Models;

use Illuminate\Database\Eloquent\Model;
use Domain\CohabitationRecords\Models\Family;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Child extends Model
{
    protected $table = 'cohabitation_children';

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at', 'family_id'];

    protected $casts = [
        'family_id' => 'integer',
        'age' => 'float',
    ];

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class, 'family_id', 'family_id');
    }
}

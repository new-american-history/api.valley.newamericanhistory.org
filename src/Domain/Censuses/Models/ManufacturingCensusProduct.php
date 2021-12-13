<?php

namespace Domain\Censuses\Models;

use Illuminate\Database\Eloquent\Model;
use Domain\Censuses\Models\ManufacturingCensus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManufacturingCensusProduct extends Model
{
    protected $table = 'manufacturing_census_products';

    protected $guarded = [];

    protected $casts = [
        'manufacturing_census_id' => 'integer',
        'value' => 'integer',
    ];

    public function manufacturing_census(): BelongsTo
    {
        return $this->belongsTo(ManufacturingCensus::class);
    }
}

<?php

namespace Domain\Censuses\Models;

use Illuminate\Database\Eloquent\Model;
use Domain\Censuses\Models\ManufacturingCensus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManufacturingCensusMaterial extends Model
{
    protected $table = 'manufacturing_census_materials';

    protected $guarded = [];

    public $timestamps = false;

    public $hidden = ['manufacturing_census_id'];

    protected $casts = [
        'manufacturing_census_id' => 'integer',
        'value' => 'integer',
        'census_data_id' => 'integer',
    ];

    public function manufacturing_census(): BelongsTo
    {
        return $this->belongsTo(ManufacturingCensus::class);
    }
}

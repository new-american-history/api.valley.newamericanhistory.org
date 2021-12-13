<?php

namespace Domain\Censuses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Domain\Censuses\Models\ManufacturingCensusProduct;
use Domain\Censuses\Models\ManufacturingCensusMaterial;

class ManufacturingCensus extends Model
{
    protected $table = 'manufacturing_census';

    protected $guarded = [];

    protected $casts = [
        'year' => 'integer',
        'months_active' => 'integer',
        'capital_invested' => 'integer',
        'machine_names' => 'integer',
        'female_hands' => 'float',
        'male_hands' => 'float',
        'child_hands' => 'float',
        'female_wages' => 'integer',
        'male_wages' => 'integer',
        'total_wages' => 'integer',
        'page_number' => 'integer',
        'number_on_page' => 'integer',
    ];

    public function materials(): HasMany
    {
        return $this->hasMany(ManufacturingCensusMaterial::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(ManufacturingCensusProduct::class);
    }
}

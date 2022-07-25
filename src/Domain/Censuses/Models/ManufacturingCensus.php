<?php

namespace Domain\Censuses\Models;

use Domain\Shared\Traits\HasCountyEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Domain\Censuses\Models\ManufacturingCensusProduct;
use Domain\Censuses\Models\ManufacturingCensusMaterial;

class ManufacturingCensus extends Model
{
    use HasCountyEnum;

    protected $table = 'manufacturing_census';

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['county_label'];

    protected $casts = [
        'year' => 'integer',
        'months_active' => 'integer',
        'capital_invested' => 'integer',
        'number_of_machines' => 'integer',
        'female_hands' => 'float',
        'male_hands' => 'float',
        'child_hands' => 'float',
        'female_wages' => 'integer',
        'male_wages' => 'integer',
        'total_wages' => 'integer',
        'page_number' => 'integer',
        'number_on_page' => 'integer',
        'data_id' => 'integer',
    ];

    public static $exactFiltersWithCommas = [
        'products.type',
    ];

    public static $exactFilters = [
        'business',
        'business_class',
        'county',
        'location',
        'year',
    ];

    public static $fuzzyFilters = [
        'machines',
        'name',

        'materials.type',
    ];

    public static $numericFilters = [
        'capital_invested',
        'child_hands',
        'data_id',
        'female_hands',
        'female_wages',
        'male_hands',
        'male_wages',
        'months_active',
        'number_of_machines',
        'page_number',
        'total_wages',

        'materials.value',
        'products.value',
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

<?php

namespace Domain\TaxRecords\Models;

use Domain\Shared\Traits\HasCountyEnum;
use Illuminate\Database\Eloquent\Model;

class AugustaTaxRecord extends Model
{
    use HasCountyEnum;

    protected $table = 'tax_records_augusta';

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['county_label'];

    protected $casts = [
        'year' => 'integer',
        'acres' => 'float',
        'rods' => 'float',
        'poles' => 'float',
        'building_value' => 'integer',
        'lot_building_value' => 'float',
        'tax_amount' => 'float',
        'city_tax_amount' => 'float',
    ];

    public static $exactFilters = [
        'county',
        'estate',
        'year',
    ];

    public static $fuzzyFilters = [
        'first_name',
        'last_name',
        'other_name',
        'residence',
    ];

    public static $numericFilters = [
        'acres',
        'building_value',
        'city_tax_amount',
        'lot_building_value',
        'poles',
        'rods',
        'tax_amount',
    ];
}

<?php

namespace Domain\TaxRecords\Models;

use Domain\Shared\Traits\HasCountyEnum;
use Illuminate\Database\Eloquent\Model;

class FranklinTaxRecord extends Model
{
    use HasCountyEnum;

    protected $table = 'tax_records_franklin';

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['county_label'];

    protected $casts = [
        'year' => 'integer',
        'county_tax_amount' => 'float',
        'state_tax_amount' => 'float',
        'state_personal_tax_amount' => 'float',
        'seated_acres' => 'float',
        'value_per_seated_acre' => 'integer',
        'seated_land_value' => 'integer',
        'unseated_acres' => 'float',
        'unseated_land_value' => 'integer',
        'number_seated_lots' => 'float',
        'seated_lot_value' => 'float',
        'number_unseated_lots' => 'float',
        'unseated_lot_value' => 'float',
    ];

    public static $exactFilters = [
        'county',
        'occupation',
        'ward',
        'year',
    ];

    public static $fuzzyFilters = [
        'first_name',
        'last_name',
        'other_name',
    ];

    public static $numericFilters = [
        'county_tax_amount',
        'number_seated_lots',
        'number_unseated_lots',
        'seated_acres',
        'seated_land_value',
        'seated_lot_value',
        'state_personal_tax_amount',
        'state_tax_amount',
        'unseated_acres',
        'unseated_land_value',
        'unseated_lot_value',
        'value_per_seated_acre',
    ];
}

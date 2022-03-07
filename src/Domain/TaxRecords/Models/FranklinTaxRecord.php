<?php

namespace Domain\TaxRecords\Models;

use Illuminate\Database\Eloquent\Model;

class FranklinTaxRecord extends Model
{
    protected $table = 'tax_records_franklin';

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

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
        'year',
        'estate',
    ];

    public static $fuzzyFilters = [
        'first_name',
        'last_name',
        'other_name',
        'residence',
    ];

    public static $numericFilters = [
        'county_tax_amount',
        'state_tax_amount',
        'state_personal_tax_amount',
        'seated_acres',
        'value_per_seated_acre',
        'seated_land_value',
        'unseated_acres',
        'unseated_land_value',
        'number_seated_lots',
        'seated_lot_value',
        'number_unseated_lots',
        'unseated_lot_value',
    ];
}

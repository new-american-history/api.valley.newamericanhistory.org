<?php

namespace Domain\TaxRecords\Models;

use Illuminate\Database\Eloquent\Model;

class FranklinTaxRecord extends Model
{
    protected $table = 'tax_records_franklin';

    protected $guarded = [];

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
}

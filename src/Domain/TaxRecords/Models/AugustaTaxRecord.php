<?php

namespace Domain\TaxRecords\Models;

use Illuminate\Database\Eloquent\Model;

class AugustaTaxRecord extends Model
{
    protected $table = 'tax_records_augusta';

    protected $guarded = [];

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
}

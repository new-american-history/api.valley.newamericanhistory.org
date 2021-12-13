<?php

namespace Domain\Censuses\Models;

use Illuminate\Database\Eloquent\Model;

class VeteranCensus extends Model
{
    protected $table = 'veteran_census';

    protected $guarded = [];

    protected $dates = [
        'enlistment_date',
        'discharge_date',
    ];

    protected $casts = [
        'year' => 'integer',
        'house_number' => 'integer',
        'superior_district_number' => 'integer',
        'page_number' => 'integer',
        'number_on_page' => 'integer',
    ];
}

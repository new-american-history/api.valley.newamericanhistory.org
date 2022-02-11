<?php

namespace Domain\Censuses\Models;

use Illuminate\Database\Eloquent\Model;

class VeteranCensus extends Model
{
    protected $table = 'veteran_census';

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

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

    public static $exactFilters = [
        'county',
        'year',
        'family_number',
        'house_number',

        'enlistment_date',
        'discharge_date',

        'company',
        'rank',
        'regiment',
    ];

    public static $fuzzyFilters = [
        'first_name',
        'last_name',
        'location',
        'post_office',

        'widow_name',
        'disability',
    ];
}

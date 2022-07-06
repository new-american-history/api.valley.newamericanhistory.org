<?php

namespace Domain\Censuses\Models;

use Domain\Shared\Traits\HasCountyEnum;
use Illuminate\Database\Eloquent\Model;

class VeteranCensus extends Model
{
    use HasCountyEnum;

    protected $table = 'veteran_census';

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['county_label'];

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
        'location',
        'family_number',
        'house_number',
        'company',
        'rank',
        'regiment',
    ];

    public static $fuzzyFilters = [
        'first_name',
        'last_name',
        'post_office',
        'widow_name',
        'disability',
    ];

    public static $dateFilters = [
        'enlistment_date',
        'discharge_date',
    ];
}

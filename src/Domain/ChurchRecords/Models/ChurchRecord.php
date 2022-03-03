<?php

namespace Domain\ChurchRecords\Models;

use Illuminate\Database\Eloquent\Model;

class ChurchRecord extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $dates = ['date'];

    public static $exactFilters = [
        'county',
        'record_type',
        'race',
        'sex'
    ];

    public static $fuzzyFilters = [
        'church_name',
        'first_name',
        'last_name',
        'clergy',
        'location',
        'family',
    ];

    // @todo Let's handle these types with greater_than and less_than comparisons,
    // similar to the numericFilters.
    public static $dateFilters = [
        'date',
        'dob',
    ];
}

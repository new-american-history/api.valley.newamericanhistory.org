<?php

namespace Domain\Censuses\Models;

use Domain\Shared\Traits\HasCountyEnum;
use Illuminate\Database\Eloquent\Model;

class SlaveowningCensus extends Model
{
    use HasCountyEnum;

    protected $table = 'slaveowning_census';

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['county_label'];

    protected $casts = [
        'year' => 'integer',
        'total_slaves' => 'integer',
        'black_slaves' => 'integer',
        'mulatto_slaves' => 'integer',
        'female_slaves' => 'integer',
        'male_slaves' => 'integer',
    ];

    public static $exactFilters = [
        'employer_location',
    ];

    public static $fuzzyFilters = [
        'employer_name',
        'first_name',
        'last_name',
        'location',
    ];

    public static $numericFilters = [
        'black_slaves',
        'female_slaves',
        'male_slaves',
        'mulatto_slaves',
        'total_slaves',
    ];
}

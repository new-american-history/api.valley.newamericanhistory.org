<?php

namespace Domain\Censuses\Models;

use Illuminate\Database\Eloquent\Model;

class SlaveowningCensus extends Model
{
    protected $table = 'slaveowning_census';

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'year' => 'integer',
        'total_slaves' => 'integer',
        'black_slaves' => 'integer',
        'mulatto_slaves' => 'integer',
        'female_slaves' => 'integer',
        'male_slaves' => 'integer',
    ];

    public static $fuzzyFilters = [
        'first_name',
        'last_name',
        'location',
        'employer_name',
        'employer_location',
    ];

    public static $numericFilters = [
        'total_slaves',
        'black_slaves',
        'mulatto_slaves',
        'female_slaves',
        'male_slaves',
    ];
}

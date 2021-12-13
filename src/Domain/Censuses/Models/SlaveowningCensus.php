<?php

namespace Domain\Censuses\Models;

use Illuminate\Database\Eloquent\Model;

class SlaveowningCensus extends Model
{
    protected $table = 'slaveowning_census';

    protected $guarded = [];

    protected $casts = [
        'year' => 'integer',
        'total_slaves' => 'integer',
        'black_slaves' => 'integer',
        'mulatto_slaves' => 'integer',
        'female_slaves' => 'integer',
        'male_slaves' => 'integer',
    ];
}

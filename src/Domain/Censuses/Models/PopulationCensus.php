<?php

namespace Domain\Censuses\Models;

use Illuminate\Database\Eloquent\Model;

class PopulationCensus extends Model
{
    protected $table = 'population_census';

    protected $guarded = [];

    protected $dates = ['date_taken'];

    protected $casts = [
        'year' => 'integer',
        'age' => 'float',
        'dwelling_number' => 'integer',
        'family_number' => 'integer',
        'head_number' => 'integer',
        'attended_school' => 'boolean',
        'cannot_read' => 'boolean',
        'cannot_write' => 'boolean',
        'father_foreign_born' => 'boolean',
        'mother_foreign_born' => 'boolean',
        'male_citizen' => 'boolean',
        'male_citizen_novote' => 'boolean',
        'married_within_the_year' => 'boolean',
        'marriage_month' => 'integer',
        'birth_month' => 'integer',
        'personal_estate_value' => 'integer',
        'real_estate_value' => 'integer',
        'page_number' => 'integer',
    ];
}

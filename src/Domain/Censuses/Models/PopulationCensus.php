<?php

namespace Domain\Censuses\Models;

use Domain\Shared\Enums\Sex;
use Domain\Shared\Enums\Race;
use Domain\Shared\Traits\HasCountyEnum;
use Illuminate\Database\Eloquent\Model;

class PopulationCensus extends Model
{
    use HasCountyEnum;

    protected $table = 'population_census';

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['county_label', 'race_label', 'sex_label'];

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

    public static $exactFilters = [
        'county',
        'year',
        'birthplace',
        'sex',
        'occupation',
        'race',
        'attended_school',
        'cannot_read',
        'cannot_write',
        'father_foreign_born',
        'mother_foreign_born',
        'marriage_month',
        'birth_month',
    ];

    public static $fuzzyFilters = [
        'first_name',
        'last_name',

        'disability',
        'district',
        'subdistrict',
    ];

    public static $numericFilters = [
        'age',
        'personal_estate_value',
        'real_estate_value',

        'dwelling_number',
        'family_number',
        'head_number',
    ];

    public static $dateFilters = [
        'date_taken'
    ];

    protected function getRaceLabelAttribute(): ?string
    {
        $enum = Race::tryFrom($this->race);
        return $enum->label ?? null;
    }

    protected function getSexLabelAttribute(): ?string
    {
        $enum = Sex::tryFrom($this->sex);
        return $enum->label ?? null;
    }
}

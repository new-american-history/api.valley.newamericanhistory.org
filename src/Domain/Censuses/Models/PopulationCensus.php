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
        'attended_school',
        'birth_month',
        'cannot_read',
        'cannot_write',
        'county',
        'father_foreign_born',
        'marriage_month',
        'mother_foreign_born',
        'occupation',
        'race',
        'sex',
        'year',
    ];

    public static $exactFiltersWithCommas = [
        'birthplace',
    ];

    public static $fuzzyFilters = [
        'disability',
        'district',
        'first_name',
        'last_name',
        'subdistrict',
    ];

    public static $numericFilters = [
        'age',
        'dwelling_number',
        'family_number',
        'head_number',
        'page_number',
        'personal_estate_value',
        'real_estate_value',
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

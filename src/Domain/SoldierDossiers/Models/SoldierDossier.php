<?php

namespace Domain\SoldierDossiers\Models;

use Domain\Shared\Models\Image;
use Domain\Shared\Traits\HasCountyEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SoldierDossier extends Model
{
    use HasCountyEnum;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['county_label'];

    protected $casts = [
        'enlisted_age' => 'integer',
        '1860_census_dwelling_number' => 'integer',
        '1860_census_family_number' => 'integer',
        '1860_census_page_number' => 'integer',
    ];

    public function images(): BelongsToMany
    {
        return $this->belongsToMany(Image::class, 'soldier_dossier_image')
            ->orderBy('weight');
    }

    public static $exactFilters = [
        'company',
        'county',
        'enlisted_rank',
        'transfer_company',
        'valley_id',
    ];

    public static $exactFiltersWithCommas = [
        'enlisted_location',
        'enlisted_occupation',
        'regiment',
    ];

    public static $fuzzyFilters = [
        'birthplace',
        'burial_location',
        'conscript_or_substitute',
        'death_location',
        'first_name',
        'hospital_record',
        'last_name',
        'muster_record',
        'personal_info',
        'postwar_life',
        'prewar_life',
        'promotions',
        'transfers',
    ];

    public static $numericFilters = [
        '1860_census_dwelling_number' => 'integer',
        '1860_census_family_number' => 'integer',
        '1860_census_page_number' => 'integer',
        'enlisted_age',
    ];

    public static $dateFilters = [
        'awol_date',
        'birthday',
        'captured_date',
        'death_date',
        'deserted_date',
        'died_of_disease_date',
        'died_of_wounds_date',
        'discharged_date',
        'enlisted_date',
        'kia_date',
        'mia_date',
        'paroled_date',
        'pow_date',
        'wia_date',
    ];
}

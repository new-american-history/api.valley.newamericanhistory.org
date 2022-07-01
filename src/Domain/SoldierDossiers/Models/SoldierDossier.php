<?php

namespace Domain\SoldierDossiers\Models;

use Domain\Shared\Models\Image;
use Domain\Shared\Traits\HasCountyEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SoldierDossier extends Model
{
    use HasCountyEnum;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at', 'image_id'];

    protected $appends = ['county_label'];

    protected $dates = [
        'birthday',
        'enlisted_date',
        'death_date',
        'awol_date',
        'captured_date',
        'deserted_date',
        'died_of_disease_date',
        'died_of_wounds_date',
        'discharged_date',
        'kia_date',
        'mia_date',
        'paroled_date',
        'pow_date',
        'wia_date',
    ];

    protected $casts = [
        'image_id' => 'integer',
        'enlisted_age' => 'integer',
        '1860_census_dwelling_number' => 'integer',
        '1860_census_family_number' => 'integer',
        '1860_census_page_number' => 'integer',
    ];

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }

    public static $exactFilters = [
        'county',
        'valley_id',
        'company',
        'transfer_company',
    ];

    public static $fuzzyFilters = [
        'first_name',
        'last_name',
        'regiment',
        'birthplace',
        'personal_info',
        'prewar_life',
        'postwar_life',
        'enlisted_location',
        'enlisted_occupation',
        'conscript_or_substitute',
        'promotions',
        'transfers',
        'muster_record',
        'hospital_record',
        'death_location',
        'burial_location',
    ];

    public static $numericFilters = [
        'enlisted_age',
    ];

    public static $dateFilters = [
        'birthday',
        'enlisted_date',
        'death_date',
        'awol_date',
        'captured_date',
        'deserted_date',
        'died_of_disease_date',
        'died_of_wounds_date',
        'discharged_date',
        'kia_date',
        'mia_date',
        'paroled_date',
        'pow_date',
        'wia_date',
    ];
}

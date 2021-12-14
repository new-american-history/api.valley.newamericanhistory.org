<?php

namespace Domain\SoldierDossiers\Models;

use Domain\Images\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SoldierDossier extends Model
{
    protected $guarded = [];

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
}

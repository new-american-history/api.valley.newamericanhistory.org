<?php

namespace Domain\CohabitationRecords\Models;

use Domain\Shared\Traits\HasCountyEnum;
use Illuminate\Database\Eloquent\Model;
use Domain\CohabitationRecords\Models\Child;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Family extends Model
{
    use HasCountyEnum;

    protected $table = 'cohabitation_families';

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['county_label'];

    protected $casts = [
        'family_id' => 'integer',
        'husband_age' => 'integer',
        'wife_age' => 'integer',
        'number_of_children' => 'integer',
    ];

    public function children(): HasMany
    {
        return $this->hasMany(Child::class, 'family_id', 'family_id')
            ->orderBy('age');
    }

    public static $exactFilters = [
        'county',
    ];

    public static $exactFiltersWithCommas = [
        'husband_birthplace',
        'residence',
        'wife_birthplace',
    ];

    public static $fuzzyFilters = [
        'husband_first_name',
        'husband_last_name',
        'husband_occupation',
        'wife_first_name',
        'wife_last_name',

        'children.name',
    ];

    public static $numericFilters = [
        'husband_age',
        'number_of_children',
        'wife_age',

        'children.age',
    ];

    public static $dateFilters = [
        'report_date',
    ];
}

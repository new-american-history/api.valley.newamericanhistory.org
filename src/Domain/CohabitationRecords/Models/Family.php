<?php

namespace Domain\CohabitationRecords\Models;

use Illuminate\Database\Eloquent\Model;
use Domain\CohabitationRecords\Models\Child;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Family extends Model
{
    protected $table = 'cohabitation_families';

    protected $guarded = [];

    protected $dates = ['report_date'];

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

    public static $fuzzyFilters = [
        'residence',
        'husband_first_name',
        'husband_last_name',
        'wife_first_name',
        'wife_last_name',
        'husband_birthplace',
        'wife_birthplace',
        'husband_occupation',

        'children.name',
    ];

    public static $numericFilters = [
        'report_date',
        'husband_age',
        'wife_age',
        'number_of_children',

        'children.age',
    ];
}

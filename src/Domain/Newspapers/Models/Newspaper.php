<?php

namespace Domain\Newspapers\Models;

use Domain\Newspapers\Models\Edition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Newspaper extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function editions(): HasMany
    {
        return $this->hasMany(Edition::class)
            ->orderBy('date');
    }

    public static $exactFilters = [
        'county',
        'state',
        'frequency',
    ];

    public static $fuzzyFilters = [
        'name',
        'city',
    ];
}

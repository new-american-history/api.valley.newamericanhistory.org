<?php

namespace Domain\FreeBlackRegistry\Models;

use Domain\Shared\Traits\HasCountyEnum;
use Illuminate\Database\Eloquent\Model;

class FreeBlackRegistry extends Model
{
    use HasCountyEnum;

    protected $table = 'free_black_registry';

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['county_label'];

    public static $exactFilters = [
        'city',
        'county',
    ];

    public static $fuzzyFilters = [
        'description',
        'name',
    ];
}

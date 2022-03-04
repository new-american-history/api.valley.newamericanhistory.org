<?php

namespace Domain\FreeBlackRegistry\Models;

use Illuminate\Database\Eloquent\Model;

class FreeBlackRegistry extends Model
{
    protected $table = 'free_black_registry';

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public static $exactFilters = [
        'county',
        'city',
    ];

    public static $fuzzyFilters = [
        'name',
        'description',
    ];
}

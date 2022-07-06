<?php

namespace Domain\MemoryArticles\Models;

use Domain\Shared\Traits\HasCountyEnum;
use Illuminate\Database\Eloquent\Model;

class MemoryArticle extends Model
{
    use HasCountyEnum;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['county_label'];

    protected $dates = ['date'];

    protected $casts = [
        'year' => 'integer',
        'keywords' => 'array',
    ];

    public function getSourceFileAttribute($value)
    {
        return !empty($value) ? url($value) : null;
    }

    public static $exactFilters = [
        'county',
        'valley_id',
    ];

    public static $fuzzyFilters = [
        'title',
        'author',
        'summary',
        'body',
        'keywords',
    ];

    public static $dateFilters = [
        'date',
    ];
}

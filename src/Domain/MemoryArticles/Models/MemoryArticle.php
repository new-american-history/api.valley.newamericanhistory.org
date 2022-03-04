<?php

namespace Domain\MemoryArticles\Models;

use Illuminate\Database\Eloquent\Model;

class MemoryArticle extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'year' => 'integer',
        'keywords' => 'array',
    ];

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

    public static $numericFilters = [
        'date',
    ];
}

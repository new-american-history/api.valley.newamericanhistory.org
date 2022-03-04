<?php

namespace Domain\MemoryArticles\Models;

use Illuminate\Database\Eloquent\Model;

class MemoryArticle extends Model
{
    protected $guarded = [];

    protected $casts = [
        'year' => 'integer',
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

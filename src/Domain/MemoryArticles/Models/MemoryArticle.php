<?php

namespace Domain\MemoryArticles\Models;

use Domain\Shared\Traits\HasTeiTags;
use Domain\Shared\Traits\HasCountyEnum;
use Illuminate\Database\Eloquent\Model;

class MemoryArticle extends Model
{
    use HasCountyEnum, HasTeiTags;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['county_label', 'clean_title', 'byline'];

    protected $casts = [
        'year' => 'integer',
        'keywords' => 'array',
    ];

    protected $teiFields = ['body'];

    public function getSourceFileAttribute($value)
    {
        return !empty($value) ? url($value) : null;
    }

    public static $exactFilters = [
        'county',
        'valley_id',
    ];

    public static $fuzzyFilters = [
        'author',
        'body',
        'keywords',
        'summary',
        'title',
    ];

    public static $dateFilters = [
        'date',
    ];

    protected function getCleanTitleAttribute(): ?string
    {
        $title = $this->title;
        $matches = [];
        preg_match('/^(Augusta|Franklin)( County)?: \"(.*),\"/', $title, $matches);
        return $matches[3] ?? $title;
    }

    protected function getBylineAttribute(): ?string
    {
        $title = $this->title;
        $author = $this->author;
        $matches = [];
        preg_match('/^(Augusta|Franklin)( County)?: \"(.*),\" by (.*)/', $title, $matches);
        return $matches[4] ?? $author;
    }
}

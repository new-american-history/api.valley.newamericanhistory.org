<?php

namespace Domain\MemoryArticles\Models;

use Domain\Shared\Traits\HasTeiTags;
use Domain\Shared\Traits\HasCountyEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class MemoryArticle extends Model
{
    use HasCountyEnum, HasTeiTags;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['county_label', 'clean_title', 'byline'];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'keywords' => 'array',
        ];
    }

    protected $teiFields = ['body'];

    protected function sourceFile(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !empty($value) ? url('/storage/data' . $value) : null,
        );
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

    protected function cleanTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->computeCleanTitle(),
        );
    }

    protected function byline(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->computeByline(),
        );
    }

    private function computeCleanTitle(): ?string
    {
        $title = $this->title;
        $matches = [];
        preg_match('/^(Augusta|Franklin)( County)?: \"(.*),\"/', $title, $matches);
        return $matches[3] ?? $title;
    }

    private function computeByline(): ?string
    {
        $title = $this->title;
        $author = $this->author;
        $matches = [];
        preg_match('/.*,\" by (.*)$/', $title, $matches);
        return !empty($matches[1]) ? trim($matches[1], ' ,') : $author;
    }
}

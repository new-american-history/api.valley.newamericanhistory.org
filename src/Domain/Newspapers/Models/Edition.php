<?php

namespace Domain\Newspapers\Models;

use Domain\Newspapers\Models\Page;
use Domain\Newspapers\Models\Newspaper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Edition extends Model
{
    protected $table = 'newspaper_editions';

    protected $guarded = [];

    protected $dates = ['date'];

    protected $casts = [
        'newspaper_id' => 'integer',
    ];

    public function newspaper(): BelongsTo
    {
        return $this->belongsTo(Newspaper::class);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'newspaper_edition_id')
            ->orderBy('number');
    }

    public static $exactFilters = [
        'newspaper_id',
        'date',
        'source_file',
        'weekday',
        'newspaper.county',
        'newspaper.state',
        'pages.stories.type',
        'pages.stories.topics.id',
    ];

    public static $fuzzyFilters = [
        'headline',
        'pages.description',
        'newspaper.name',
        'newspaper.city',
        'pages.stories.headline',
        'pages.stories.summary',
        'pages.stories.body',
        'pages.stories.origin',
        'pages.stories.excerpt',
        'pages.stories.trailer',
        'pages.stories.names.prefix',
        'pages.stories.names.first_name',
        'pages.stories.names.last_name',
        'pages.stories.names.suffix',
        'pages.stories.topics.name',
    ];
}

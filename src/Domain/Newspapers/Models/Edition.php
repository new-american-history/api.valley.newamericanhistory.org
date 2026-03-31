<?php

namespace Domain\Newspapers\Models;

use Domain\Shared\Enums\Weekday;
use Domain\Newspapers\Models\Page;
use Domain\Newspapers\Enums\Frequency;
use Domain\Newspapers\Models\Newspaper;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Edition extends Model
{
    protected $table = 'newspaper_editions';

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at', 'newspaper_id'];

    protected $appends = ['frequency_label', 'weekday_label'];

    protected function casts(): array
    {
        return [
            'newspaper_id' => 'integer',
        ];
    }

    protected function pdf(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !empty($value) ? url('/storage/data' . $value) : null,
        );
    }

    protected function sourceFile(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !empty($value) ? url('/storage/data' . $value) : null,
        );
    }

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
        'frequency',
        'newspaper_id',
        'source_file',
        'weekday',

        'newspaper.county',
        'newspaper.state',

        'pages.stories.topics.id',
        'pages.stories.type',
    ];

    public static $fuzzyFilters = [
        'headline',

        'newspaper.abbreviation',
        'newspaper.city',
        'newspaper.id',
        'newspaper.name',

        'pages.description',

        'pages.stories.body',
        'pages.stories.excerpt',
        'pages.stories.headline',
        'pages.stories.origin',
        'pages.stories.summary',
        'pages.stories.trailer',

        'pages.stories.names.first_name',
        'pages.stories.names.last_name',
        'pages.stories.names.prefix',
        'pages.stories.names.suffix',

        'pages.stories.topics.name',
    ];

    public static $dateFilters = [
        'date',
    ];

    protected function frequencyLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => Frequency::tryFrom($this->frequency)?->label(),
        );
    }

    protected function weekdayLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => Weekday::tryFrom($this->weekday)?->label(),
        );
    }
}

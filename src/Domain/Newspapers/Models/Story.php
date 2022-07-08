<?php

namespace Domain\Newspapers\Models;

use Domain\Newspapers\Models\Name;
use Domain\Newspapers\Models\Page;
use Domain\Newspapers\Models\Topic;
use Domain\Shared\Traits\HasTeiTags;
use Domain\Newspapers\Enums\StoryType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Story extends Model
{
    use HasTeiTags;

    protected $table = 'newspaper_stories';

    protected $guarded = [];

    protected $hidden = ['newspaper_page_id'];

    protected $appends = ['type_label'];

    public $timestamps = false;

    protected $casts = [
        'newspaper_page_id' => 'integer',
        'weight' => 'integer',
    ];

    protected $teiFields = ['body'];

    public function names(): HasMany
    {
        return $this->hasMany(Name::class, 'newspaper_story_id')
            ->orderBy('weight');
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'newspaper_page_id');
    }

    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(
            Topic::class,
            'newspaper_story_topic',
            'newspaper_story_id',
            'newspaper_topic_id'
        );
    }

    public static $exactFilters = [
        'topics.id',
        'type',

        'page.edition.frequency',
        'page.edition.newspaper_id',
        'page.edition.source_file',
        'page.edition.weekday',

        'page.edition.newspaper.county',
        'page.edition.newspaper.state',
    ];

    public static $fuzzyFilters = [
        'body',
        'excerpt',
        'headline',
        'origin',
        'summary',
        'trailer',

        'names.first_name',
        'names.last_name',
        'names.prefix',
        'names.suffix',

        'page.description',

        'topics.name',

        'page.edition.headline',

        'page.edition.newspaper.abbreviation',
        'page.edition.newspaper.city',
        'page.edition.newspaper.id',
        'page.edition.newspaper.name',
    ];

    public static $dateFilters = [
        'page.edition.date',
    ];

    protected function getTypeLabelAttribute(): ?string
    {
        $enum = StoryType::tryFrom($this->type);
        return $enum->label ?? null;
    }
}

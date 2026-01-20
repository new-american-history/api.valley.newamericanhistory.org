<?php

namespace Domain\Newspapers\Models;

use Domain\Shared\Enums\Chapter;
use Domain\Newspapers\Models\Story;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Topic extends Model
{
    protected $table = 'newspaper_topics';

    protected $guarded = [];

    protected $hidden = ['pivot', 'parent_id'];

    protected $appends = ['chapter_label'];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'parent_id' => 'integer',
        ];
    }

    public function stories(): BelongsToMany
    {
        return $this->belongsToMany(
            Story::class,
            'newspaper_story_topic',
            'newspaper_topic_id',
            'newspaper_story_id'
        );
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public static $exactFilters = [
        'chapter',
        'parent_id',
    ];

    public static $fuzzyFilters = [
        'name',
        'parent.name',
    ];

    protected function chapterLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => Chapter::tryFrom($this->chapter)?->label,
        );
    }
}

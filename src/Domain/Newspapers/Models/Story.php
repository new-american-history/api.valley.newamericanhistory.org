<?php

namespace Domain\Newspapers\Models;

use Domain\Newspapers\Models\Name;
use Domain\Newspapers\Models\Page;
use Domain\Newspapers\Models\Topic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Story extends Model
{
    protected $table = 'newspaper_stories';

    protected $guarded = [];

    protected $hidden = ['newspaper_page_id'];

    public $timestamps = false;

    protected $casts = [
        'newspaper_page_id' => 'integer',
        'weight' => 'integer',
    ];

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
}

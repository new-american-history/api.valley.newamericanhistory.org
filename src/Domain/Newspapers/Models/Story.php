<?php

namespace Domain\Newspapers\Models;

use Domain\Newspapers\Models\Name;
use Domain\Newspapers\Models\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Story extends Model
{
    protected $table = 'newspaper_stories';

    protected $guarded = [];

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
}

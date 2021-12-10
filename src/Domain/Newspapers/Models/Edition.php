<?php

namespace Domain\Newspapers\Models;

use Domain\Newspapers\Models\Page;
use Domain\Newspapers\Models\Topic;
use Domain\Newspapers\Models\Newspaper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(
            Topic::class,
            'newspaper_edition_topic',
            'newspaper_edition_id',
            'newspaper_topic_id'
        );
    }
}

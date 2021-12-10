<?php

namespace Domain\Newspapers\Models;

use Domain\Newspapers\Models\Edition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Topic extends Model
{
    protected $table = 'newspaper_topics';

    protected $guarded = [];

    protected $casts = [
        'parent_id' => 'integer',
    ];

    public function editions(): BelongsToMany
    {
        return $this->belongsToMany(
            Edition::class,
            'newspaper_edition_topic',
            'newspaper_topic_id',
            'newspaper_edition_id'
        );
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }
}

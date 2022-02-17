<?php

namespace Domain\Newspapers\Models;

use Domain\Newspapers\Models\Story;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Name extends Model
{
    protected $table = 'newspaper_names';

    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'newspaper_story_id' => 'integer',
        'weight' => 'integer',
    ];

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class, 'newspaper_story_id');
    }
}

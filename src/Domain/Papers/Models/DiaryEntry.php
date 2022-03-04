<?php

namespace Domain\Papers\Models;

use Domain\Papers\Models\Diary;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiaryEntry extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at', 'diary_id', 'weight'];

    protected $dates = ['date'];

    protected $casts = [
        'diary_id' => 'integer',
        'weight' => 'integer',
    ];

    public function diary(): BelongsTo
    {
        return $this->belongsTo(Diary::class);
    }
}

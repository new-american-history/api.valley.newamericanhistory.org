<?php

namespace Domain\Papers\Models;

use Domain\Papers\Models\Diary;
use Domain\Shared\Traits\HasTeiTags;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiaryEntry extends Model
{
    use HasTeiTags;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at', 'diary_id', 'weight'];

    protected $casts = [
        'diary_id' => 'integer',
        'weight' => 'integer',
    ];

    protected $teiFields = [
        'body',
        'headline',
    ];

    public function diary(): BelongsTo
    {
        return $this->belongsTo(Diary::class);
    }
}

<?php

namespace Domain\Papers\Models;

use Domain\Papers\Models\Note;
use Domain\Papers\Models\DiaryEntry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Diary extends Model
{
    protected $guarded = [];

    protected $dates = ['date'];

    public function entries(): HasMany
    {
        return $this->hasMany(DiaryEntry::class)
            ->orderBy('weight');
    }

    public function notes(): BelongsToMany
    {
        return $this->belongsToMany(Note::class);
    }
}

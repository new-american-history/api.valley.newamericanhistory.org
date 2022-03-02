<?php

namespace Domain\Papers\Models;

use Domain\Shared\Models\Note;
use Domain\Shared\Models\Image;
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

    public function images(): BelongsToMany
    {
        return $this->belongsToMany(Image::class, 'diary_image')
            ->orderBy('weight');
    }

    public function notes(): BelongsToMany
    {
        return $this->belongsToMany(Note::class);
    }

    public static $exactFilters = [
        'county',
        'valley_id',
        'start_date',
        'end_date',
        'entries.date',
    ];

    public static $fuzzyFilters = [
        'title',
        'author',
        'bio',
        'entries.headline',
        'entries.body',
        'keywords',
    ];
}

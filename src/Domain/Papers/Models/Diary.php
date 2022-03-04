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

    protected $hidden = ['created_at', 'updated_at'];

    protected $dates = ['start_date', 'end_date'];

    protected $casts = [
        'keywords' => 'array',
    ];

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

        'entries.date',
    ];

    public static $fuzzyFilters = [
        'title',
        'author',
        'bio',
        'keywords',

        'entries.headline',
        'entries.body',
    ];

    public static $numericFilters = [
        'start_date',
        'end_date',
    ];
}

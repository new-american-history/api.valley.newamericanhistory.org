<?php

namespace Domain\Papers\Models;

use Domain\Shared\Models\Note;
use Domain\Shared\Models\Image;
use Domain\Papers\Models\DiaryEntry;
use Domain\Shared\Traits\HasTeiTags;
use Domain\Shared\Traits\HasCountyEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Diary extends Model
{
    use HasCountyEnum, HasTeiTags;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['county_label'];

    protected $casts = [
        'keywords' => 'array',
    ];

    protected $teiFields = [
        'bio',
    ];

    public function getSourceFileAttribute($value)
    {
        return !empty($value) ? url($value) : null;
    }

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
        'author',
        'county',
        'keywords',
        'valley_id',
    ];

    public static $fuzzyFilters = [
        'bio',
        'title',

        'entries.body',
        'entries.headline',
    ];

    public static $dateFilters = [
        'end_date',
        'start_date',

        'entries.date',
    ];
}

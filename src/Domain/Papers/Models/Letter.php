<?php

namespace Domain\Papers\Models;

use Domain\Shared\Models\Note;
use Domain\Shared\Models\Image;
use Domain\Shared\Traits\HasTeiTags;
use Domain\Shared\Traits\HasCountyEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Letter extends Model
{
    use HasCountyEnum, HasTeiTags;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['county_label', 'clean_title'];

    protected $casts = [
        'keywords' => 'array',
    ];

    protected $teiFields = [
        'body',
        'closing_salutation',
        'epigraph',
        'headline',
        'location',
        'signed',
    ];

    public function getSourceFileAttribute($value)
    {
        return !empty($value) ? url('/storage/data' . $value) : null;
    }

    public function images(): BelongsToMany
    {
        return $this->belongsToMany(Image::class, 'letter_image')
            ->orderBy('weight');
    }

    public function notes(): BelongsToMany
    {
        return $this->belongsToMany(Note::class);
    }

    public static $exactFilters = [
        'author',
        'collection',
        'county',
        'valley_id',
    ];

    public static $fuzzyFilters = [
        'headline',
        'keywords',
        'location',
        'recipient',
        'summary',
        'title',
    ];

    public static $dateFilters = [
        'date',
    ];

    protected function getCleanTitleAttribute(): ?string
    {
        $title = $this->title;
        $title = preg_replace('/^\w+ County: /', '', $title);
        $title = preg_replace('/, \w+ \d+, \d+$/', '', $title);
        return $title;
    }
}

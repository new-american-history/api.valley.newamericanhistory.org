<?php

namespace Domain\Papers\Models;

use Domain\Shared\Models\Note;
use Domain\Shared\Models\Image;
use Domain\Shared\Traits\HasCountyEnum;
use Illuminate\Database\Eloquent\Model;
use Domain\Shared\Traits\HasModernSpelling;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Letter extends Model
{
    use HasCountyEnum, HasModernSpelling;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['county_label'];

    protected $dates = ['date'];

    protected $casts = [
        'keywords' => 'array',
    ];

    protected $modernFields = [
        'body',
        'closing_salutation',
        'epigraph',
        'headline',
        'location',
        'signed',
    ];

    public function getSourceFileAttribute($value)
    {
        return !empty($value) ? url($value) : null;
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
        'county',
        'valley_id',
        'collection',
    ];

    public static $fuzzyFilters = [
        'title',
        'author',
        'headline',
        'summary',
        'recipient',
        'location',
        'keywords',
    ];

    public static $numericFilters = [
        'date',
    ];
}

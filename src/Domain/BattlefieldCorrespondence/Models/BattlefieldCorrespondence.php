<?php

namespace Domain\BattlefieldCorrespondence\Models;

use Domain\Shared\Models\Note;
use Domain\Shared\Traits\HasTeiTags;
use Domain\Shared\Traits\HasCountyEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BattlefieldCorrespondence extends Model
{
    use HasCountyEnum, HasTeiTags;

    protected $table = 'battlefield_correspondence';

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['county_label'];

    protected $casts = [
        'keywords' => 'array',
    ];

    protected $teiFields = [
        'body',
        'headline',
        'location',
        'postscript',
        'recipient',
        'signed',
    ];

    public function getSourceFileAttribute($value)
    {
        return !empty($value) ? url('/storage/data' . $value) : null;
    }

    public function notes(): BelongsToMany
    {
        return $this->belongsToMany(Note::class);
    }

    public static $exactFilters = [
        'county',
        'valley_id',
    ];

    public static $fuzzyFilters = [
        'author',
        'headline',
        'keywords',
        'location',
        'postscript',
        'recipient',
        'summary',
        'title',
    ];

    public static $dateFilters = [
        'date',
    ];
}

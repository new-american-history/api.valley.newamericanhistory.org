<?php

namespace Domain\BattlefieldCorrespondence\Models;

use Domain\Shared\Models\Note;
use Domain\Shared\Traits\HasCountyEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BattlefieldCorrespondence extends Model
{
    use HasCountyEnum;

    protected $table = 'battlefield_correspondence';

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['county_label'];

    protected $dates = ['date'];

    protected $casts = [
        'keywords' => 'array',
    ];

    public function notes(): BelongsToMany
    {
        return $this->belongsToMany(Note::class);
    }

    public static $exactFilters = [
        'county',
        'valley_id',
    ];

    public static $fuzzyFilters = [
        'title',
        'author',
        'summary',
        'headline',
        'recipient',
        'location',
        'postscript',
        'keywords',
    ];

    public static $numericFilters = [
        'date',
    ];
}

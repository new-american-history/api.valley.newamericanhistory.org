<?php

namespace Domain\BattlefieldCorrespondence\Models;

use Domain\Shared\Models\Note;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BattlefieldCorrespondence extends Model
{
    protected $table = 'battlefield_correspondence';

    protected $guarded = [];

    protected $dates = ['date'];

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

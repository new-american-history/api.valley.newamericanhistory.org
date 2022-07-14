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

    protected $appends = ['county_label', 'clean_title', 'date_from_title'];

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

    protected $cleanRegExpList = [
        'dateMonthDayYearWithQuotemark' =>
            '/," ([0-9]{0,2} ?\[?(January|February|March|April|May|June|July|August|September|October|November|December).*)$/i',
        'dateMonthDayYear' =>
            '/, ([0-9]{0,2} ?\[?(January|February|March|April|May|June|July|August|September|October|November|December).*)$/i',
        'day' => '/, ((Sunday|Monday|Tuesday|Wednesday|Thursday|Friday|Saturday).*)$/i',
        'dateBraces' => '/, (\[.+\].*)$/i',
        'dateCirca' => '/, ((c.|ca.|circa).+)$/i',
        'dateYear' => '/, ([0-9X?]{4})$/i',
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

        // Remove various date formats.
        $title = preg_replace($this->cleanRegExpList['dateMonthDayYearWithQuotemark'], '"', $title);
        $title = preg_replace($this->cleanRegExpList['dateMonthDayYear'], '', $title);
        $title = preg_replace($this->cleanRegExpList['day'], '', $title);
        $title = preg_replace($this->cleanRegExpList['dateBraces'], '', $title);
        $title = preg_replace($this->cleanRegExpList['dateCirca'], '', $title);
        $title = preg_replace($this->cleanRegExpList['dateYear'], '', $title);

        return $title;
    }

    protected function getDateFromTitleAttribute(): ?string
    {
        $title = $this->title;
        if (preg_match($this->cleanRegExpList['dateMonthDayYearWithQuotemark'], $title, $matches)) {
            return $matches[1] ?? null;
        } elseif (preg_match($this->cleanRegExpList['dateMonthDayYear'], $title, $matches)) {
            return $matches[1] ?? null;
        } elseif (preg_match($this->cleanRegExpList['day'], $title, $matches)) {
            return $matches[1] ?? null;
        } elseif (preg_match($this->cleanRegExpList['dateBraces'], $title, $matches)) {
            return $matches[1] ?? null;
        } elseif (preg_match($this->cleanRegExpList['dateCirca'], $title, $matches)) {
            return $matches[1] ?? null;
        } elseif (preg_match($this->cleanRegExpList['dateYear'], $title, $matches)) {
            return $matches[1] ?? null;
        }
        return null;
    }
}

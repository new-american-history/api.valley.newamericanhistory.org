<?php

namespace Domain\ChurchRecords\Models;

use Domain\Shared\Enums\Sex;
use Domain\Shared\Traits\HasCountyEnum;
use Illuminate\Database\Eloquent\Model;
use Domain\ChurchRecords\Enums\RecordType;

class ChurchRecord extends Model
{
    use HasCountyEnum;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['county_label', 'sex_label', 'record_type_label'];

    public static $exactFilters = [
        'county',
        'church_name',
        'date_written',
        'record_type',
        'sex',
        'location',
    ];

    public static $fuzzyFilters = [
        'first_name',
        'last_name',
        'clergy',
        'family',
        'race',
    ];

    public static $dateFilters = [
        'date',
        'dob',
    ];

    protected function getSexLabelAttribute(): ?string
    {
        $enum = Sex::tryFrom($this->sex);
        return $enum->label ?? null;
    }

    protected function getRecordTypeLabelAttribute(): ?string
    {
        $enum = RecordType::tryFrom($this->record_type);
        return $enum->label ?? null;
    }
}

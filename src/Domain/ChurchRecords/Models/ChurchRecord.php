<?php

namespace Domain\ChurchRecords\Models;

use Domain\Shared\Enums\Sex;
use Illuminate\Database\Eloquent\Model;
use Domain\ChurchRecords\Enums\RecordType;

class ChurchRecord extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['sex_label', 'record_type_label'];

    protected $dates = ['date'];

    public static $exactFilters = [
        'county',
        'record_type',
        'sex',
    ];

    public static $fuzzyFilters = [
        'church_name',
        'first_name',
        'last_name',
        'date_written',
        'clergy',
        'location',
        'family',
        'race',
    ];

    public static $numericFilters = [
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

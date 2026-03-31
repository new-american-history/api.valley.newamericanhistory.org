<?php

namespace Domain\Claims\Models;

use Domain\Shared\Enums\Sex;
use Domain\Shared\Enums\Race;
use Domain\Shared\Models\Image;
use Domain\Shared\Traits\HasCountyEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Domain\Claims\Models\ChambersburgClaimBuilding;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChambersburgClaim extends Model
{
    use HasCountyEnum;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['county_label', 'race_label', 'sex_label'];

    protected function casts(): array
    {
        return [
            'claim_number' => 'integer',
            'claim_total' => 'float',
            'personal_property' => 'float',
            'real_property' => 'float',
            'amount_awarded' => 'float',
            'amount_received' => 'float',
        ];
    }

    public function buildings(): HasMany
    {
        return $this->hasMany(ChambersburgClaimBuilding::class, 'possible_claim_id');
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }

    public static $exactFilters = [
        'county',
        'race',
        'sex',
    ];

    public static $fuzzyFilters = [
        'first_name',
        'last_name',
    ];

    public static $numericFilters = [
        'amount_awarded',
        'amount_received',
        'claim_date',
        'claim_number',
        'claim_total',
        'personal_property',
        'real_property',
    ];

    public static $dateFilters = [
        'claim_date',
    ];

    protected function raceLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => Race::tryFrom($this->race)?->label(),
        );
    }

    protected function sexLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => Sex::tryFrom($this->sex)?->label(),
        );
    }
}

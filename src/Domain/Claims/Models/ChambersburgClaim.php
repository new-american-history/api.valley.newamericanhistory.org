<?php

namespace Domain\Claims\Models;

use Domain\Shared\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Domain\Claims\Models\ChambersburgClaimBuilding;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChambersburgClaim extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $dates = ['claim_date'];

    protected $casts = [
        'claim_number' => 'integer',
        'claim_total' => 'float',
        'personal_property' => 'float',
        'real_property' => 'float',
        'amount_awarded' => 'float',
        'amount_received' => 'float',
    ];

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

    public static $numbericFilters = [
        'claim_number',
        'claim_date',

        'claim_total',
        'personal_property',
        'real_property',
        'amount_awarded',
    ];
}

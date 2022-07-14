<?php

namespace Domain\Claims\Models;

use Domain\Shared\Traits\HasTeiTags;
use Domain\Shared\Traits\HasCountyEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Domain\Claims\Models\SouthernClaimsCommissionItem;
use Domain\Claims\Models\SouthernClaimsCommissionTestimony;

class SouthernClaimsCommissionClaim extends Model
{
    use HasCountyEnum, HasTeiTags;

    protected $table = 'southern_claims_commission';

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['county_label'];

    protected $casts = [
        'keywords' => 'array',
    ];

    protected $teiFields = ['commission_summary'];

    public function getSourceFileAttribute($value)
    {
        return !empty($value) ? url('/storage/data' . $value) : null;
    }

    public function items(): HasMany
    {
        return $this->hasMany(SouthernClaimsCommissionItem::class, 'claim_id')
            ->orderBy('weight');
    }

    public function testimonies(): HasMany
    {
        return $this->hasMany(SouthernClaimsCommissionTestimony::class, 'claim_id')
            ->orderBy('weight');
    }

    public static $exactFilters = [
        'county',
        'valley_id'
    ];

    public static $fuzzyFilters = [
        'author',
        'commission_summary',
        'keywords',
        'summary',
        'title',

        'items.item',

        'testimonies.attestant',
        'testimonies.body',
    ];

    public static $numericFilters = [
        'items.amount_allowed',
        'items.amount_claimed',
        'items.amount_disallowed',
    ];

    public static $dateFilters = [
        'date',
    ];
}

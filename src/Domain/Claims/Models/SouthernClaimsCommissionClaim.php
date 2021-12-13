<?php

namespace Domain\Claims\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Domain\Claims\Models\SouthernClaimsCommissionItem;
use Domain\Claims\Models\SouthernClaimsCommissionTestimony;

class SouthernClaimsCommissionClaim extends Model
{
    protected $table = 'southern_claims_commission';

    protected $guarded = [];

    protected $dates = ['date'];

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
}

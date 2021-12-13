<?php

namespace Domain\Claims\Models;

use Illuminate\Database\Eloquent\Model;
use Domain\Claims\Models\SouthernClaimsCommissionClaim;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SouthernClaimsCommissionTestimony extends Model
{
    protected $guarded = [];

    protected $casts = [
        'claim_id' => 'integer',
        'weight' => 'float',
    ];

    public function claim(): BelongsTo
    {
        return $this->belongsTo(SouthernClaimsCommissionClaim::class, 'claim_id');
    }
}

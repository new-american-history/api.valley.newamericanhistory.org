<?php

namespace Domain\Claims\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Domain\Claims\Models\SouthernClaimsCommissionClaim;

class SouthernClaimsCommissionItem extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'claim_id' => 'integer',
        'amount_claimed' => 'float',
        'amount_allowed' => 'float',
        'amount_disallowed' => 'float',
        'weight' => 'float',
    ];

    public function claim(): BelongsTo
    {
        return $this->belongsTo(SouthernClaimsCommissionClaim::class, 'claim_id');
    }
}

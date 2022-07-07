<?php

namespace Domain\Claims\Models;

use Domain\Shared\Traits\HasTeiTags;
use Illuminate\Database\Eloquent\Model;
use Domain\Claims\Models\SouthernClaimsCommissionClaim;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SouthernClaimsCommissionTestimony extends Model
{
    use HasTeiTags;

    protected $guarded = [];

    protected $hidden = ['claim_id', 'weight'];

    public $timestamps = false;

    protected $casts = [
        'claim_id' => 'integer',
        'weight' => 'float',
    ];

    protected $teiFields = ['body'];

    public function claim(): BelongsTo
    {
        return $this->belongsTo(SouthernClaimsCommissionClaim::class, 'claim_id');
    }
}

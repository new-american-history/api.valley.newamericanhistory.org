<?php

namespace Domain\Claims\Models;

use Domain\Shared\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Domain\Claims\Models\ChambersburgClaim;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChambersburgClaimBuilding extends Model
{
    protected $guarded = [];

    protected $casts = [
        'building_number' => 'integer',
        'possible_claim_id' => 'integer',
        'image_id' => 'integer',
    ];

    public function possible_claim(): BelongsTo
    {
        return $this->belongsTo(ChambersburgClaim::class, 'possible_claim_id');
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}

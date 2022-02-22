<?php

namespace Domain\Claims\Models;

use Domain\Shared\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChambersburgClaim extends Model
{
    protected $guarded = [];

    protected $dates = ['claim_date'];

    protected $casts = [
        'claim_number' => 'integer',
        'claim_total' => 'float',
        'personal_property' => 'float',
        'real_property' => 'float',
        'amount_awarded' => 'float',
        'amount_received' => 'float',
        'image_id' => 'integer',
    ];

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}

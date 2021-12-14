<?php

namespace Domain\FireInsurancePolicies\Models;

use Domain\Images\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FireInsurancePolicy extends Model
{
    protected $guarded = [];

    protected $casts = [
        'image_id' => 'integer',
    ];

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}

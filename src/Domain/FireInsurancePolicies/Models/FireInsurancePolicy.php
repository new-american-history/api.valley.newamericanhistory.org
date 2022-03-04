<?php

namespace Domain\FireInsurancePolicies\Models;

use Domain\Shared\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FireInsurancePolicy extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at', 'image_id'];

    protected $casts = [
        'image_id' => 'integer',
    ];

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }

    public static $exactFilters = [
        'county',
        'policy_number',
    ];

    public static $fuzzyFilters = [
        'name',
        'description',
    ];
}

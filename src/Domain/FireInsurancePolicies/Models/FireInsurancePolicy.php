<?php

namespace Domain\FireInsurancePolicies\Models;

use Domain\Shared\Models\Image;
use Domain\Shared\Traits\HasCountyEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FireInsurancePolicy extends Model
{
    use HasCountyEnum;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at', 'image_id'];

    protected $appends = ['county_label'];

    protected $casts = [
        'image_id' => 'integer',
    ];

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }

    public static $exactFilters = [
        'county',
    ];

    public static $fuzzyFilters = [
        'description',
        'name',
    ];

    public static $numericFilters = [
        'policy_number',
    ];
}

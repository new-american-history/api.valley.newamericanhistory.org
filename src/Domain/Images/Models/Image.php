<?php

namespace Domain\Images\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    protected $guarded = [];

    protected $casts = [
        'width' => 'integer',
        'height' => 'integer',
        'source_id' => 'integer',
    ];

    public function source(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}

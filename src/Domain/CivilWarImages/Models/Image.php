<?php

namespace Domain\CivilWarImages\Models;

use Domain\CivilWarImages\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    protected $table = 'civil_war_images';

    protected $guarded = [];

    protected $casts = [
        'subject_id' => 'integer',
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}

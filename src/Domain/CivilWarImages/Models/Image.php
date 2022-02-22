<?php

namespace Domain\CivilWarImages\Models;

use Illuminate\Database\Eloquent\Model;
use Domain\CivilWarImages\Models\Subject;
use Domain\Shared\Models\Image as SharedImage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    protected $table = 'civil_war_images';

    protected $guarded = [];

    protected $casts = [
        'image_id' => 'integer',
        'subject_id' => 'integer',
    ];

    public function image(): BelongsTo
    {
        return $this->belongsTo(SharedImage::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}

<?php

namespace Domain\CivilWarImages\Models;

use Illuminate\Database\Eloquent\Model;
use Domain\CivilWarImages\Models\Subject;
use Domain\CivilWarImages\Enums\ImageType;
use Domain\Shared\Models\Image as SharedImage;
use Domain\CivilWarImages\Enums\OriginalSource;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    protected $table = 'civil_war_images';

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['image_type_label', 'original_source_label'];

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

    public static $exactFilters = [
        'image_type',
        'original_source',
        'person_name',

        'subject.name',
    ];

    public static $fuzzyFilters = [
        'artist',
        'contributing_source',
        'date',
        'description',
        'location',
        'regiment',
        'title',
    ];

    protected function getImageTypeLabelAttribute(): ?string
    {
        $enum = ImageType::tryFrom($this->image_type);
        return $enum->label ?? null;
    }

    protected function getOriginalSourceLabelAttribute(): ?string
    {
        $enum = OriginalSource::tryFrom($this->original_source);
        return $enum->label ?? null;
    }
}

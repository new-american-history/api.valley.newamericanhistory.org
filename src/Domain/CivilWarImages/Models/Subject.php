<?php

namespace Domain\CivilWarImages\Models;

use Domain\CivilWarImages\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $table = 'civil_war_image_subjects';

    protected $guarded = [];

    public $timestamps = false;

    public function images(): HasMany
    {
        return $this->hasMany(Image::class, 'subject_id');
    }
}

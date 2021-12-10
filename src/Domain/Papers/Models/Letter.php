<?php

namespace Domain\Papers\Models;

use Domain\Papers\Models\Note;
use Domain\Images\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Letter extends Model
{
    protected $guarded = [];

    protected $dates = ['date'];

    public function images(): BelongsToMany
    {
        return $this->belongsToMany(Image::class, 'letter_image')
            ->withPivot('weight')
            ->orderBy('weight');
    }

    public function notes(): BelongsToMany
    {
        return $this->belongsToMany(Note::class);
    }
}

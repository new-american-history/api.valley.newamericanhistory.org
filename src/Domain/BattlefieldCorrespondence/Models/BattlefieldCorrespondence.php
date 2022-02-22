<?php

namespace Domain\BattlefieldCorrespondence\Models;

use Domain\Papers\Shared\Note;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BattlefieldCorrespondence extends Model
{
    protected $table = 'battlefield_correspondence';

    protected $guarded = [];

    protected $dates = ['date'];

    public function notes(): BelongsToMany
    {
        return $this->belongsToMany(Note::class);
    }
}

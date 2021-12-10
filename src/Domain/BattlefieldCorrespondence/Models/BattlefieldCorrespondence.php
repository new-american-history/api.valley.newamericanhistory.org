<?php

namespace Domain\BattlefieldCorrespondence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BattlefieldCorrespondence extends Model
{
    protected $table = 'battlefield_correspondence';

    protected $guarded = [];

    protected $dates = ['date'];
}

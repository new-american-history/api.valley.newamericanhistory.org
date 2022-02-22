<?php

namespace Domain\Shared\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $guarded = [];

    protected $casts = [
        'number' => 'integer',
    ];
}

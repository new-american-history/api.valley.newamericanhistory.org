<?php

namespace Domain\Papers\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $guarded = [];

    protected $casts = [
        'number' => 'integer',
    ];
}

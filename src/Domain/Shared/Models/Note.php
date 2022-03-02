<?php

namespace Domain\Shared\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at', 'pivot'];

    protected $casts = [
        'number' => 'integer',
    ];
}

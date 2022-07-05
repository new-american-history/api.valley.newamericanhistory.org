<?php

namespace Domain\Shared\Models;

use Domain\Shared\Traits\HasTeiTags;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasTeiTags;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at', 'pivot'];

    protected $casts = [
        'number' => 'integer',
    ];

    protected $teiFields = ['body'];
}

<?php

namespace Domain\MemoryArticles\Models;

use Illuminate\Database\Eloquent\Model;

class MemoryArticle extends Model
{
    protected $guarded = [];

    protected $casts = [
        'year' => 'integer',
    ];
}

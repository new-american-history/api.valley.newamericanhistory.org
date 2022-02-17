<?php

namespace Domain\Newspapers\Models;

use Domain\Newspapers\Models\Story;
use Domain\Newspapers\Models\Edition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model
{
    protected $table = 'newspaper_pages';

    protected $guarded = [];

    public $timestamps = false;

    protected $dates = ['date'];

    protected $casts = [
        'newspaper_edition_id' => 'integer',
        'number' => 'integer',
    ];

    public function edition(): BelongsTo
    {
        return $this->belongsTo(Edition::class, 'newspaper_edition_id');
    }

    public function stories(): HasMany
    {
        return $this->hasMany(Story::class, 'newspaper_page_id')
            ->orderBy('weight');
    }
}

<?php

namespace Domain\Shared\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at', 'pivot'];

    protected function casts(): array
    {
        return [
            'width' => 'integer',
            'height' => 'integer',
            'source_id' => 'integer',
        ];
    }

    protected $appends = [
        'url'
    ];

    protected $storageDirectory = 'images/';

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => !empty($this->path)
                ? url(Storage::url($this->storageDirectory . $this->path))
                : null,
        );
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}

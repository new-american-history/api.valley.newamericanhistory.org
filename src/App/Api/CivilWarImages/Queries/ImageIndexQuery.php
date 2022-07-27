<?php

namespace App\Api\CivilWarImages\Queries;

use Illuminate\Http\Request;
use Domain\CivilWarImages\Models\Image;
use Support\Queries\IndexQueryBuilder;

class ImageIndexQuery extends IndexQueryBuilder
{
    public function __construct(Request $request)
    {
        $query = Image::query()->with(['image', 'subject']);

        parent::__construct($query, $request);

        $this->allowedFilters($this->mapAllowedFilters(Image::class));
        $this->allowedSorts($this->mapAllowedSorts(Image::class));
        $this->defaultSort('id');
    }
}

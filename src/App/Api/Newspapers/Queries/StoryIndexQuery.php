<?php

namespace App\Api\Newspapers\Queries;

use Illuminate\Http\Request;
use Domain\Newspapers\Models\Story;
use Spatie\QueryBuilder\AllowedFilter;
use Support\Queries\IndexQueryBuilder;
use Illuminate\Database\Eloquent\Builder;

class StoryIndexQuery extends IndexQueryBuilder
{
    public function __construct(Request $request)
    {
        $query = Story::query();

        parent::__construct($query, $request);

        $this->allowedFilters($this->mapAllowedFilters(Story::class));
        $this->allowedSorts($this->mapAllowedSorts(Story::class));
    }
}

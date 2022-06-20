<?php

namespace App\Api\Newspapers\Queries;

use Illuminate\Http\Request;
use Domain\Newspapers\Models\Topic;
use Support\Queries\IndexQueryBuilder;

class TopicIndexQuery extends IndexQueryBuilder
{
    public function __construct(Request $request)
    {
        $query = Topic::query();

        parent::__construct($query, $request);

        $this->allowedFilters($this->mapAllowedFilters(Topic::class));
        $this->allowedSorts($this->mapAllowedSorts(Topic::class));
        $this->defaultSort('name');
    }
}

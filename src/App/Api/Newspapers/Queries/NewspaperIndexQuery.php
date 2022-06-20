<?php

namespace App\Api\Newspapers\Queries;

use Illuminate\Http\Request;
use Support\Queries\IndexQueryBuilder;
use Domain\Newspapers\Models\Newspaper;

class NewspaperIndexQuery extends IndexQueryBuilder
{
    public function __construct(Request $request)
    {
        $query = Newspaper::query();

        parent::__construct($query, $request);

        $this->allowedFilters($this->mapAllowedFilters(Newspaper::class));
        $this->allowedSorts($this->mapAllowedSorts(Newspaper::class));
        $this->defaultSort('name');
    }
}

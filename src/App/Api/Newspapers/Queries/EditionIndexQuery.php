<?php

namespace App\Api\Newspapers\Queries;

use Illuminate\Http\Request;
use Domain\Newspapers\Models\Edition;
use Support\Queries\IndexQueryBuilder;

class EditionIndexQuery extends IndexQueryBuilder
{
    public function __construct(Request $request)
    {
        $query = Edition::query();

        parent::__construct($query, $request);

        $this->allowedFilters($this->mapAllowedFilters(Edition::class));
        $this->defaultSort('date');
    }
}

<?php

namespace App\Api\Censuses\Queries;

use Illuminate\Http\Request;
use Support\Queries\IndexQueryBuilder;
use Domain\Censuses\Models\PopulationCensus;

class PopulationCensusIndexQuery extends IndexQueryBuilder
{
    public function __construct(Request $request)
    {
        $query = PopulationCensus::query();

        parent::__construct($query, $request);

        $this->allowedFilters($this->mapAllowedFilters(PopulationCensus::class));
    }
}

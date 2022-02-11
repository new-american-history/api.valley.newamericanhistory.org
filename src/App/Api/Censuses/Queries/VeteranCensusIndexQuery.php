<?php

namespace App\Api\Censuses\Queries;

use Illuminate\Http\Request;
use Support\Queries\IndexQueryBuilder;
use Domain\Censuses\Models\VeteranCensus;

class VeteranCensusIndexQuery extends IndexQueryBuilder
{
    public function __construct(Request $request)
    {
        $query = VeteranCensus::query();

        parent::__construct($query, $request);

        $this->allowedFilters($this->mapAllowedFilters(VeteranCensus::class));
    }
}

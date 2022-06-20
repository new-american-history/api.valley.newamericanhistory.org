<?php

namespace App\Api\Censuses\Queries;

use Illuminate\Http\Request;
use Support\Queries\IndexQueryBuilder;
use Domain\Censuses\Models\ManufacturingCensus;

class ManufacturingCensusIndexQuery extends IndexQueryBuilder
{
    public function __construct(Request $request)
    {
        $query = ManufacturingCensus::query()
            ->with(['materials', 'products']);

        parent::__construct($query, $request);

        $this->allowedFilters($this->mapAllowedFilters(ManufacturingCensus::class));
        $this->allowedSorts($this->mapAllowedSorts(ManufacturingCensus::class));
    }
}

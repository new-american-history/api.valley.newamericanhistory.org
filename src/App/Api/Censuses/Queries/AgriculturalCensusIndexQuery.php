<?php

namespace App\Api\Censuses\Queries;

use Illuminate\Http\Request;
use Support\Queries\IndexQueryBuilder;
use Domain\Censuses\Models\AgriculturalCensus;

class AgriculturalCensusIndexQuery extends IndexQueryBuilder
{
    public function __construct(Request $request)
    {
        $query = AgriculturalCensus::query();

        parent::__construct($query, $request);

        $this->allowedFilters($this->mapAllowedFilters(AgriculturalCensus::class));
        $this->allowedSorts($this->mapAllowedSorts(AgriculturalCensus::class));
        $this->defaultSort('last_name');
    }
}

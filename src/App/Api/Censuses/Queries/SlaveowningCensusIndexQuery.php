<?php

namespace App\Api\Censuses\Queries;

use Illuminate\Http\Request;
use Support\Queries\IndexQueryBuilder;
use Domain\Censuses\Models\SlaveowningCensus;

class SlaveowningCensusIndexQuery extends IndexQueryBuilder
{
    public function __construct(Request $request)
    {
        $query = SlaveowningCensus::query();

        parent::__construct($query, $request);

        $this->allowedFilters($this->mapAllowedFilters(SlaveowningCensus::class));
    }
}

<?php

namespace App\Api\Claims\Queries;

use Illuminate\Http\Request;
use Support\Queries\IndexQueryBuilder;
use Domain\Claims\Models\ChambersburgClaim;

class ChambersburgClaimIndexQuery extends IndexQueryBuilder
{
    public function __construct(Request $request)
    {
        $query = ChambersburgClaim::query()->with(['buildings', 'buildings.image']);

        parent::__construct($query, $request);

        $this->allowedFilters($this->mapAllowedFilters(ChambersburgClaim::class));
        $this->allowedSorts($this->mapAllowedSorts(ChambersburgClaim::class));
        $this->defaultSort('last_name');
    }
}

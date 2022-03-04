<?php

namespace App\Api\Claims\Queries;

use Illuminate\Http\Request;
use Support\Queries\IndexQueryBuilder;
use Domain\Claims\Models\SouthernClaimsCommissionClaim;

class SouthernClaimsCommissionIndexQuery extends IndexQueryBuilder
{
    public function __construct(Request $request)
    {
        $query = SouthernClaimsCommissionClaim::query();

        parent::__construct($query, $request);

        $this->allowedFilters($this->mapAllowedFilters(SouthernClaimsCommissionClaim::class));
        $this->defaultSort('date');
    }
}

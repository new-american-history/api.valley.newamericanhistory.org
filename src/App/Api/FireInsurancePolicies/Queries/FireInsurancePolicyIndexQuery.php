<?php

namespace App\Api\FireInsurancePolicies\Queries;

use Illuminate\Http\Request;
use Support\Queries\IndexQueryBuilder;
use Domain\FireInsurancePolicies\Models\FireInsurancePolicy;

class FireInsurancePolicyIndexQuery extends IndexQueryBuilder
{
    public function __construct(Request $request)
    {
        $query = FireInsurancePolicy::query()->with('image');

        parent::__construct($query, $request);

        $this->allowedFilters($this->mapAllowedFilters(FireInsurancePolicy::class));
        $this->allowedSorts($this->mapAllowedSorts(FireInsurancePolicy::class));
        $this->defaultSort('id');
    }
}

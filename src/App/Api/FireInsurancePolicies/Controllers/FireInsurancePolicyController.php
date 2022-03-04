<?php

namespace App\Api\FireInsurancePolicies\Controllers;

use Illuminate\Http\Request;
use Domain\FireInsurancePolicies\Models\FireInsurancePolicy;
use App\Api\FireInsurancePolicies\Queries\FireInsurancePolicyIndexQuery;
use App\Api\FireInsurancePolicies\Resources\FireInsurancePolicyResource;

class FireInsurancePolicyController
{
    public function index(FireInsurancePolicyIndexQuery $query)
    {
        return FireInsurancePolicyResource::collection(
            $query->paginate($request->perpage ?? 100)
        );
    }
}

<?php

namespace App\Api\Claims\Controllers;

use Illuminate\Http\Request;
use Domain\Claims\Models\SouthernClaimsCommissionClaim;
use App\Api\Claims\Queries\SouthernClaimsCommissionIndexQuery;
use App\Api\Claims\Resources\SouthernClaimsCommissionClaimResource;

class SouthernClaimsCommissionController
{
    public function index(
        Request $request,
        SouthernClaimsCommissionIndexQuery $query
    ) {
        return SouthernClaimsCommissionClaimResource::collection(
            $query->paginate($request->perpage ?? 100)
        );
    }

    public function show(Request $request, string $valley_id)
    {
        $claim = SouthernClaimsCommissionClaim::where(['valley_id' => $valley_id])->firstOrFail();
        $res = new SouthernClaimsCommissionClaimResource($claim);
        return $res->toFull($request);
    }
}

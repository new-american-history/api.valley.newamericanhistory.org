<?php

namespace App\Api\Claims\Controllers;

use Illuminate\Http\Request;
use App\Api\Claims\Queries\ChambersburgClaimIndexQuery;
use App\Api\Claims\Resources\ChambersburgClaimResource;

class ChambersburgClaimController
{
    public function index(Request $request, ChambersburgClaimIndexQuery $query)
    {
        return ChambersburgClaimResource::collection(
            $query->paginate($request->perpage ?? 100)
        );
    }
}

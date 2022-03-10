<?php

namespace App\Api\Censuses\Controllers;

use Illuminate\Http\Request;
use App\Api\Censuses\Resources\CensusResource;
use App\Api\Censuses\Queries\VeteranCensusIndexQuery;

class VeteranCensusController
{
    public function index(Request $request, VeteranCensusIndexQuery $query)
    {
        return CensusResource::collection(
            $query->paginate($request->perpage ?? 100)
        );
    }
}

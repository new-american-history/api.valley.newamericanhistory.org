<?php

namespace App\Api\Censuses\Controllers;

use Illuminate\Http\Request;
use App\Api\Censuses\Queries\ManufacturingCensusIndexQuery;
use App\Api\Censuses\Resources\ManufacturingCensusResource;

class ManufacturingCensusController
{
    public function index(
        Request $request,
        ManufacturingCensusIndexQuery $query
    ) {
        return ManufacturingCensusResource::collection(
            $query->paginate($request->perpage ?? 100)
        );
    }
}

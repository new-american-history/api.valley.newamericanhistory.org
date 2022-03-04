<?php

namespace App\Api\Censuses\Controllers;

use Illuminate\Http\Request;
use App\Api\Censuses\Resources\CensusResource;
use App\Api\Censuses\Queries\PopulationCensusIndexQuery;

class PopulationCensusController
{
    public function index(Request $request, PopulationCensusIndexQuery $query)
    {
        return CensusResource::collection(
            $query->paginate($request->perpage ?? 50)
        );
    }
}

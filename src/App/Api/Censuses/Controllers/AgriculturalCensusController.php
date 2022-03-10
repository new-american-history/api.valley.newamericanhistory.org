<?php

namespace App\Api\Censuses\Controllers;

use Illuminate\Http\Request;
use App\Api\Censuses\Resources\CensusResource;
use App\Api\Censuses\Queries\AgriculturalCensusIndexQuery;

class AgriculturalCensusController
{
    public function index(Request $request, AgriculturalCensusIndexQuery $query)
    {
        return CensusResource::collection(
            $query->paginate($request->perpage ?? 100)
        );
    }
}

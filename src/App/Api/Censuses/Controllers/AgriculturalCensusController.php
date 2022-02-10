<?php

namespace App\Api\Censuses\Controllers;

use Illuminate\Http\Request;
use App\Api\Censuses\Resources\CensusResource;
use Domain\Censuses\Models\AgriculturalCensus;
use App\Api\Censuses\Queries\AgriculturalCensusIndexQuery;

class AgriculturalCensusController
{
    public function index(AgriculturalCensusIndexQuery $query)
    {
        return CensusResource::collection(
            $query->paginate($request->perpage ?? 50)
        );
    }
}

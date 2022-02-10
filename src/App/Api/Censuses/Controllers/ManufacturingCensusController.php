<?php

namespace App\Api\Censuses\Controllers;

use Illuminate\Http\Request;
use App\Api\Censuses\Resources\ManufacturingCensusResource;
use Domain\Censuses\Models\ManufacturingCensus;
use App\Api\Censuses\Queries\ManufacturingCensusIndexQuery;

class ManufacturingCensusController
{
    public function index(ManufacturingCensusIndexQuery $query)
    {
        return ManufacturingCensusResource::collection(
            $query->paginate($request->perpage ?? 50)
        );
    }
}

<?php

namespace App\Api\Censuses\Controllers;

use Illuminate\Http\Request;
use App\Api\Censuses\Resources\CensusResource;
use App\Api\Censuses\Queries\SlaveowningCensusIndexQuery;

class SlaveowningCensusController
{
    public function index(Request $request, SlaveowningCensusIndexQuery $query)
    {
        return CensusResource::collection(
            $query->paginate($request->perpage ?? 50)
        );
    }
}

<?php

namespace App\Api\Censuses\Controllers;

use Illuminate\Http\Request;
use Domain\Censuses\Models\AgriculturalCensus;
use App\Api\Censuses\Queries\AgriculturalCensusIndexQuery;

class AgriculturalCensusController
{
    public function index(AgriculturalCensusIndexQuery $query) {
        $perpage = $request->perpage ?? 50;
        $records = $query->paginate($perpage);
        return $records;
        // return AgriculturalCensusResource::collection($records);
    }
}

<?php

namespace App\Api\ChurchRecords\Controllers;

use Illuminate\Http\Request;
use Domain\ChurchRecords\Models\ChurchRecord;
use App\Api\ChurchRecords\Queries\ChurchRecordIndexQuery;
use App\Api\ChurchRecords\Resources\ChurchRecordResource;

class ChurchRecordController
{
    public function index(Request $request, ChurchRecordIndexQuery $query)
    {
        return ChurchRecordResource::collection(
            $query->paginate($request->perpage ?? 100)
        );
    }
}

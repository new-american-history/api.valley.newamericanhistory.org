<?php

namespace App\Api\TaxRecords\Controllers;

use Illuminate\Http\Request;
use App\Api\TaxRecords\Queries\AugustaTaxRecordIndexQuery;
use App\Api\TaxRecords\Resources\AugustaTaxRecordResource;

class AugustaTaxRecordController
{
    public function index(Request $request, AugustaTaxRecordIndexQuery $query)
    {
        return AugustaTaxRecordResource::collection(
            $query->paginate($request->perpage ?? 50)
        );
    }
}

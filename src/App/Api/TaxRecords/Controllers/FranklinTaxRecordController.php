<?php

namespace App\Api\TaxRecords\Controllers;

use Illuminate\Http\Request;
use App\Api\TaxRecords\Queries\FranklinTaxRecordIndexQuery;
use App\Api\TaxRecords\Resources\FranklinTaxRecordResource;

class FranklinTaxRecordController
{
    public function index(Request $request, FranklinTaxRecordIndexQuery $query)
    {
        return FranklinTaxRecordResource::collection(
            $query->paginate($request->perpage ?? 50)
        );
    }
}

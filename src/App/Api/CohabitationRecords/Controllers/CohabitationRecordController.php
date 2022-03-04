<?php

namespace App\Api\CohabitationRecords\Controllers;

use Illuminate\Http\Request;
use App\Api\CohabitationRecords\Resources\FamilyResource;
use App\Api\CohabitationRecords\Queries\FamilyIndexQuery;

class CohabitationRecordController
{
    public function index(Request $request, FamilyIndexQuery $query)
    {
        return FamilyResource::collection(
            $query->paginate($request->perpage ?? 50)
        );
    }
}

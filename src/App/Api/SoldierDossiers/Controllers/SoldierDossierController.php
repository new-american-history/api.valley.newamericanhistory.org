<?php

namespace App\Api\SoldierDossiers\Controllers;

use Illuminate\Http\Request;
use App\Api\SoldierDossiers\Queries\SoldierDossierIndexQuery;
use App\Api\SoldierDossiers\Resources\SoldierDossierResource;

class SoldierDossierController
{
    public function index(Request $request, SoldierDossierIndexQuery $query)
    {
        return SoldierDossierResource::collection(
            $query->paginate($request->perpage ?? 50)
        );
    }
}

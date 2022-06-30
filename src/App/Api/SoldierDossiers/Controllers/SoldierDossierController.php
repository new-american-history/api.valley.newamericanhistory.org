<?php

namespace App\Api\SoldierDossiers\Controllers;

use Illuminate\Http\Request;
use Domain\SoldierDossiers\Models\SoldierDossier;
use App\Api\SoldierDossiers\Queries\SoldierDossierIndexQuery;
use App\Api\SoldierDossiers\Resources\SoldierDossierResource;

class SoldierDossierController
{
    public function index(Request $request, SoldierDossierIndexQuery $query)
    {
        return SoldierDossierResource::collection(
            $query->paginate($request->perpage ?? 100)
        );
    }

    public function show(string $valley_id)
    {
        $soldierDossier = SoldierDossier::where(['valley_id' => $valley_id])->firstOrFail();
        return new SoldierDossierResource($soldierDossier);
    }
}

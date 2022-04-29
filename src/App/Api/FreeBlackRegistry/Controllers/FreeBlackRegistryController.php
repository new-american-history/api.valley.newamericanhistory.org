<?php

namespace App\Api\FreeBlackRegistry\Controllers;

use Illuminate\Http\Request;
use App\Api\FreeBlackRegistry\Queries\FreeBlackRegistryIndexQuery;
use App\Api\FreeBlackRegistry\Resources\FreeBlackRegistryResource;

class FreeBlackRegistryController
{
    public function index(Request $request, FreeBlackRegistryIndexQuery $query)
    {
        return FreeBlackRegistryResource::collection(
            $query->paginate($request->perpage ?? 100)
        );
    }
}

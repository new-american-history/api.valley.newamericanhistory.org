<?php

namespace App\Api\FreeBlackRegistry\Controllers;

use Illuminate\Http\Request;
use App\Api\FreeBlackRegistry\Queries\FreeBlackRegistryIndexQuery;
use App\Api\FreeBlackRegistry\Resources\FreeBlackRegistryResource;

class FreeBlackRegistryController
{
    public function index(FreeBlackRegistryIndexQuery $query)
    {
        return FreeBlackRegistryResource::collection(
            $query->paginate($request->perpage ?? 50)
        );
    }
}

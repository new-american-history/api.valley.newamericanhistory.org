<?php

namespace App\Api\RegimentalMovements\Controllers;

use Illuminate\Http\Request;
use App\Api\RegimentalMovements\Resources\RegimentalMovementResource;
use App\Api\RegimentalMovements\Queries\RegimentalMovementIndexQuery;

class RegimentalMovementController
{
    public function index(Request $request, RegimentalMovementIndexQuery $query)
    {
        return RegimentalMovementResource::collection(
            $query->paginate($request->perpage ?? 50)
        );
    }
}

<?php

namespace App\Api\Newspapers\Controllers;

use Illuminate\Http\Request;
use App\Api\Newspapers\Resources\EditionResource;
use App\Api\Newspapers\Queries\EditionIndexQuery;

class EditionController
{
    public function index(Request $request, EditionIndexQuery $query)
    {
        return EditionResource::collection(
            $query->paginate($request->perpage ?? 50)
        );
    }
}

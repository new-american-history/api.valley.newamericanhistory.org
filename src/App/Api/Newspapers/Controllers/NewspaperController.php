<?php

namespace App\Api\Newspapers\Controllers;

use Illuminate\Http\Request;
use App\Api\Newspapers\Resources\NewspaperResource;
use App\Api\Newspapers\Queries\NewspaperIndexQuery;

class NewspaperController
{
    public function index(NewspaperIndexQuery $query)
    {
        return NewspaperResource::collection(
            $query->paginate($request->perpage ?? 50)
        );
    }
}

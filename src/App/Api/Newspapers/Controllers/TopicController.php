<?php

namespace App\Api\Newspapers\Controllers;

use Illuminate\Http\Request;
use App\Api\Newspapers\Resources\TopicResource;
use App\Api\Newspapers\Queries\TopicIndexQuery;

class TopicController
{
    public function index(Request $request, TopicIndexQuery $query)
    {
        return TopicResource::collection(
            $query->paginate($request->perpage ?? 50)
        );
    }
}

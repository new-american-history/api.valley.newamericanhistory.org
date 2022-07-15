<?php

namespace App\Api\Newspapers\Controllers;

use Illuminate\Http\Request;
use App\Api\Newspapers\Resources\StoryResource;
use App\Api\Newspapers\Queries\StoryIndexQuery;

class StoryController
{
    public function index(Request $request, StoryIndexQuery $query)
    {
        $query->with(['page:number', 'page.edition', 'page.edition.newspaper'])
            ->whereNotNull('headline')
            ->whereNotNull('summary');
        $request->merge(['withRelationships' => 'true']);

        return StoryResource::collection(
            $query->paginate($request->perpage ?? 50)
        );
    }
}

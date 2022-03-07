<?php

namespace App\Api\CivilWarImages\Controllers;

use Illuminate\Http\Request;
use Domain\CivilWarImages\Models\Image;
use App\Api\CivilWarImages\Queries\ImageIndexQuery;
use App\Api\CivilWarImages\Resources\ImageResource;

class CivilWarImageController
{
    public function index(Request $request, ImageIndexQuery $query)
    {
        return ImageResource::collection(
            $query->paginate($request->perpage ?? 50)
        );
    }

    public function show(int $id)
    {
        $civilWarImage = Image::findOrFail($id);
        return new ImageResource($civilWarImage);
    }
}

<?php

namespace App\Api\Newspapers\Controllers;

use Illuminate\Http\Request;
use Domain\Newspapers\Models\Edition;
use Domain\Newspapers\Models\Newspaper;
use App\Api\Newspapers\Resources\EditionResource;
use App\Api\Newspapers\Resources\NewspaperResource;
use App\Api\Newspapers\Queries\NewspaperIndexQuery;

class NewspaperController
{
    public function index(Request $request, NewspaperIndexQuery $query)
    {
        return NewspaperResource::collection(
            $query->paginate($request->perpage ?? 50)
        );
    }

    public function showEdition(
        Request $request,
        string $slug,
        string $year,
        string $month,
        string $day
    ) {
        $newspaper = Newspaper::where(['slug' => $slug])->firstOrFail();
        $edition = Edition::where(['newspaper_id' => $newspaper->id])
            ->where(['date' => "{$year}-{$month}-{$day}"])
            ->firstOrFail();
        $res = new EditionResource($edition);

        // Manually wrap to match other resources.
        return response()->json(['data' => $res->toFull($request)]);
    }
}

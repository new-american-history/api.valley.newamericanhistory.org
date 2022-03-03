<?php

namespace App\Api\Papers\Controllers;

use Illuminate\Http\Request;
use Domain\Papers\Models\Diary;
use App\Api\Papers\Queries\DiaryIndexQuery;
use App\Api\Papers\Resources\DiaryResource;

class DiaryController
{
    public function index(DiaryIndexQuery $query)
    {
        return DiaryResource::collection(
            $query->paginate($request->perpage ?? 50)
        );
    }

    public function show(string $valley_id)
    {
        $diary = Diary::where(['valley_id' => $valley_id])->firstOrFail();
        return new DiaryResource($diary);
    }
}

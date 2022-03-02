<?php

namespace App\Api\Papers\Controllers;

use Illuminate\Http\Request;
use Domain\Papers\Models\Letter;
use App\Api\Papers\Queries\LetterIndexQuery;
use App\Api\Papers\Resources\LetterResource;

class LetterController
{
    public function index(LetterIndexQuery $query)
    {
        return LetterResource::collection(
            $query->paginate($request->perpage ?? 50)
        );
    }

    public function show(string $valley_id) {
        $letter = Letter::where(['valley_id' => $valley_id])->firstOrFail();
        return new LetterResource($letter);
    }
}

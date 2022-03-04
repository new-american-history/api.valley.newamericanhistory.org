<?php

namespace App\Api\Papers\Controllers;

use Illuminate\Http\Request;
use App\Api\Papers\Queries\BattlefieldCorrespondenceIndexQuery;
use App\Api\Papers\Resources\BattlefieldCorrespondenceResource;
use Domain\BattlefieldCorrespondence\Models\BattlefieldCorrespondence;

class BattlefieldCorrespondenceController
{
    public function index(BattlefieldCorrespondenceIndexQuery $query)
    {
        return BattlefieldCorrespondenceResource::collection(
            $query->paginate($request->perpage ?? 50)
        );
    }

    public function show(string $valley_id)
    {
        $battlefieldCorrespondence = BattlefieldCorrespondence::where(['valley_id' => $valley_id])->firstOrFail();
        return new BattlefieldCorrespondenceResource($battlefieldCorrespondence);
    }
}
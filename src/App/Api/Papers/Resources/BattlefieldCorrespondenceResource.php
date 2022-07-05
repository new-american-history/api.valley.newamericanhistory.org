<?php

namespace App\Api\Papers\Resources;

use App\Api\Papers\Resources\NoteResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BattlefieldCorrespondenceResource extends JsonResource
{
    public function toArray($request)
    {
        $res = $this->resource->toArrayWithModernSpelling();

        $res += [
            'notes' => $this->notes && $this->notes->count() > 0
                ? $this->notes->map(function ($note) {
                    return new NoteResource($note);
                }) : null,
        ];

        return $res;
    }
}

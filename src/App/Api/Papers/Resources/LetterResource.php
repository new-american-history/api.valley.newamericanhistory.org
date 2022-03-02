<?php

namespace App\Api\Papers\Resources;

use App\Api\Papers\Resources\NoteResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LetterResource extends JsonResource
{
    public function toArray($request)
    {
        $res = parent::toArray($request);

        $res += [
            // @todo Include images.
            'notes' => $this->notes && $this->notes->count() > 0
                ? $this->notes->map(function ($note) {
                    return new NoteResource($note);
                }) : null,
        ];

        return $res;
    }
}

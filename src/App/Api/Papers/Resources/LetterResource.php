<?php

namespace App\Api\Papers\Resources;

use App\Api\Papers\Resources\NoteResource;
use App\Api\Images\Resources\ImageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LetterResource extends JsonResource
{
    public function toArray($request)
    {
        $res = $this->resource->toArrayWithModernSpelling();

        $res += [
            'images' => $this->images && $this->images->count() > 0
                ? $this->images->map(function ($image) {
                    return new ImageResource($image);
                }) : null,
            'notes' => $this->notes && $this->notes->count() > 0
                ? $this->notes->map(function ($note) {
                    return new NoteResource($note);
                }) : null,
        ];

        return $res;
    }
}

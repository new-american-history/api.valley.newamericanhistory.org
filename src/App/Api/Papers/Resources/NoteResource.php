<?php

namespace App\Api\Papers\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{
    public function toArray($request)
    {
        return $this->resource->toArrayWithModernSpelling();
    }
}

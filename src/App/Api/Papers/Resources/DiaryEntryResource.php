<?php

namespace App\Api\Papers\Resources;

use App\Api\Papers\Resources\NoteResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DiaryEntryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource->toArrayWithModernSpelling();
    }
}

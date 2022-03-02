<?php

namespace App\Api\Papers\Resources;

use App\Api\Papers\Resources\NoteResource;
use App\Api\Papers\Resources\DiaryEntryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DiaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $res = parent::toArray($request);

        $res += [
            // @todo Include images.
            'entries' => $this->entries && $this->entries->count() > 0
                ? $this->entries->map(function ($note) {
                    return new DiaryEntryResource($note);
                }) : null,
            'notes' => $this->notes && $this->notes->count() > 0
                ? $this->notes->map(function ($note) {
                    return new NoteResource($note);
                }) : null,
        ];

        return $res;
    }
}

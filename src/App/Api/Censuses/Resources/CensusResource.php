<?php

namespace App\Api\Censuses\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CensusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}

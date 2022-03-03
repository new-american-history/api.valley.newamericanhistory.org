<?php

namespace App\Api\ChurchRecords\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChurchRecordResource extends JsonResource
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

<?php

namespace App\Api\Censuses\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ManufacturingCensusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            $this->merge($this->attributesToArray()),
            'materials' => $this->materials,
            'products' => $this->products,
        ];
    }
}

<?php

namespace App\Api\Censuses\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ManufacturingCensusResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            $this->merge($this->attributesToArray()),
            'materials' => $this->materials,
            'products' => $this->products,
        ];
    }
}

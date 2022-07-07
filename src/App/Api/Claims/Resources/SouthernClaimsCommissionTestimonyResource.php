<?php

namespace App\Api\Claims\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SouthernClaimsCommissionTestimonyResource extends JsonResource
{
    public function toArray($request)
    {
        return $this->resource->toArrayWithModernSpelling();
    }
}

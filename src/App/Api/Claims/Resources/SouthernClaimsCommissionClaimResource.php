<?php

namespace App\Api\Claims\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Api\Claims\Resources\SouthernClaimsCommissionItemResource;
use App\Api\Claims\Resources\SouthernClaimsCommissionTestimonyResource;

class SouthernClaimsCommissionClaimResource extends JsonResource
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public function toFull($request)
    {
        $res = parent::toArray($request);

        $res += [
            'items' => $this->items && $this->items->count() >0
                ? $this->items->map(function ($item) {
                    return new SouthernClaimsCommissionItemResource($item);
                }) : null,
            'testimonies' => $this->testimonies && $this->testimonies->count() >0
                ? $this->testimonies->map(function ($testimony) {
                    return new SouthernClaimsCommissionTestimonyResource($testimony);
                }) : null,
        ];

        return $res;
    }
}

<?php

namespace App\Api\Claims\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Api\Claims\Resources\ChambersburgClaimBuildingResource;

class ChambersburgClaimResource extends JsonResource
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
            'buildings' => $this->buildings && $this->buildings->count() >0
                ? $this->buildings->map(function ($building) {
                    return new ChambersburgClaimBuildingResource($building);
                }) : null,
        ];

        return $res;
    }
}

<?php

namespace App\Api\Claims\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChambersburgClaimBuildingResource extends JsonResource
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
            'image' => $this->image,
        ];

        return $res;
    }
}

<?php

namespace App\Api\Claims\Resources;

use App\Api\Images\Resources\ImageResource;
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
            'image' => !empty($this->image)
                ? new ImageResource($this->image)
                : null,
        ];

        return $res;
    }
}

<?php

namespace App\Api\SoldierDossiers\Resources;

use App\Api\Images\Resources\ImageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SoldierDossierResource extends JsonResource
{
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

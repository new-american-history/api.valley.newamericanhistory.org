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
            'images' => $this->images && $this->images->count() > 0
                ? $this->images->map(function ($image) {
                    return new ImageResource($image);
                }) : null,
        ];

        return $res;
    }
}

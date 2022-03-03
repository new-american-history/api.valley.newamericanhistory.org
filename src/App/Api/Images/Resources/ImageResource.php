<?php

namespace App\Api\Images\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}

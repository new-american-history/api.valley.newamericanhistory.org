<?php

namespace App\Api\Censuses\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CensusResource extends JsonResource
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}

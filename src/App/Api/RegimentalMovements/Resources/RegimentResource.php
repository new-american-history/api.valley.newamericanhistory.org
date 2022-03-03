<?php

namespace App\Api\RegimentalMovements\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RegimentResource extends JsonResource
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}

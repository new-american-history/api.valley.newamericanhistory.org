<?php

namespace App\Api\RegimentalMovements\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Api\RegimentalMovements\Resources\RegimentResource;

class RegimentalMovementResource extends JsonResource
{
    public function toArray($request)
    {
        $res = parent::toArray($request);

        $res += [
            'regiment' => !empty($this->regiment)
                ? new RegimentResource($this->regiment)
                : null,
        ];

        return $res;
    }
}

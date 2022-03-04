<?php

namespace App\Api\Newspapers\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NameResource extends JsonResource
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}

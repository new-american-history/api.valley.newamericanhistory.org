<?php

namespace App\Api\CohabitationRecords\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChildResource extends JsonResource
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}

<?php

namespace App\Api\Newspapers\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewspaperResource extends JsonResource
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}

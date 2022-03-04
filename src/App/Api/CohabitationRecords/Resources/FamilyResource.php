<?php

namespace App\Api\CohabitationRecords\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Api\CohabitationRecords\Resources\ChildResource;

class FamilyResource extends JsonResource
{
    public function toArray($request)
    {
        $res = parent::toArray($request);

        $res += [
            'children' => $this->children && $this->children->count() > 0
                ? $this->children->map(function ($child) {
                    return new ChildResource($child);
                }) : null,
        ];

        return $res;
    }
}

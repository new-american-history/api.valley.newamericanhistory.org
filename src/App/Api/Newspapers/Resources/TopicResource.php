<?php

namespace App\Api\Newspapers\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TopicResource extends JsonResource
{
    public function toArray($request)
    {
        $res = parent::toArray($request);

        $res += [
            'parent' => !empty($this->parent)
                ? new self($this->parent)
                : null,
        ];

        return $res;
    }
}

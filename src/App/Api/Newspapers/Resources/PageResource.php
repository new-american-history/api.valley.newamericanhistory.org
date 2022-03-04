<?php

namespace App\Api\Newspapers\Resources;

use App\Api\Newspapers\Resources\StoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    public function toArray($request)
    {
        $res = parent::toArray($request);

        $res += [
            'stories' => !empty($this->stories)
                ? $this->stories->map(function ($story) {
                    return new StoryResource($story);
                }) : null,
        ];

        return $res;
    }
}

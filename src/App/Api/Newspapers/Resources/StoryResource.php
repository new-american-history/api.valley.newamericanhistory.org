<?php

namespace App\Api\Newspapers\Resources;

use App\Api\Newspapers\Resources\TopicResource;
use Illuminate\Http\Resources\Json\JsonResource;

class StoryResource extends JsonResource
{
    public function toArray($request)
    {
        $res = parent::toArray($request);

        $res += [
            'names' => !empty($this->names)
                ? $this->names->map(function ($name) {
                    return new NameResource($name);
                }) : null,
            'topics' => !empty($this->topics)
                ? $this->topics->map(function ($topic) {
                    return new TopicResource($topic);
                }) : null,
        ];

        return $res;
    }
}

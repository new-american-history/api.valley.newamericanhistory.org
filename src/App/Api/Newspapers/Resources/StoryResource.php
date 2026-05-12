<?php

namespace App\Api\Newspapers\Resources;

use App\Api\Newspapers\Resources\EditionResource;
use App\Api\Newspapers\Resources\TopicResource;
use Illuminate\Http\Resources\Json\JsonResource;

class StoryResource extends JsonResource
{
    public function toArray($request)
    {
        $res = $this->resource->toArrayWithModernSpelling();

        $res += [
            'page' => !empty($this->page)
                ? [
                    'number' => $this->page->number,
                    'edition' => !empty($this->page->edition)
                        ? new EditionResource($this->page->edition)
                        : null,
                ]
                : null,
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

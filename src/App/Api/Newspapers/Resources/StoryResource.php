<?php

namespace App\Api\Newspapers\Resources;

use App\Api\Newspapers\Resources\TopicResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Api\Newspapers\Resources\EditionResource;
use App\Api\Newspapers\Resources\NewspaperResource;

class StoryResource extends JsonResource
{
    public function toArray($request)
    {
        $res = $this->resource->toArrayWithModernSpelling();

        if ($request->withRelationships) {
            $res += [
                'newspaper' => !empty($this->page->edition->newspaper)
                    ? new NewspaperResource($this->page->edition->newspaper)
                    : null,
                'edition' => !empty($this->page->edition)
                    ? new EditionResource($this->page->edition)
                    : null,
                'page_number' => !empty($this->page)
                    ? $this->page->number
                    : null,
            ];
        } else {
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
        }

        return $res;
    }
}

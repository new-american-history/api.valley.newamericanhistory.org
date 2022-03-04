<?php

namespace App\Api\MemoryArticles\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MemoryArticleResource extends JsonResource
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}

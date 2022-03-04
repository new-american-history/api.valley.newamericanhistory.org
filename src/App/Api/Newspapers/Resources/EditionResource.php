<?php

namespace App\Api\Newspapers\Resources;

use App\Api\Newspapers\Resources\PageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class EditionResource extends JsonResource
{
    public function toArray($request)
    {
        $res = parent::toArray($request);

        $res += [
            'newspaper' => !empty($this->newspaper)
                ? new NewspaperResource($this->newspaper)
                : null,
        ];

        return $res;
    }

    public function toFull($request)
    {
        $res = $this->toArray($request);

        $res += [
            'pages' => !empty($this->pages)
                ? $this->pages->map(function ($page) {
                    return new PageResource($page);
                }) : null,
        ];

        return $res;
    }
}

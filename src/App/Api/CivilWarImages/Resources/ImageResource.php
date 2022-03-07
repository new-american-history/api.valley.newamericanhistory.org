<?php

namespace App\Api\CivilWarImages\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Api\Images\Resources\ImageResource as SharedImageResource;

class ImageResource extends JsonResource
{
    public function toArray($request)
    {
        $res = parent::toArray($request);

        $res += [
            'image' => !empty($this->image)
                ? new SharedImageResource($this->image)
                : null,
            'subject' => $this->subject ?? null,
        ];

        return $res;
    }
}

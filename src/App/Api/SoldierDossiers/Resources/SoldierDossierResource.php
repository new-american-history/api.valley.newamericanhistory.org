<?php

namespace App\Api\SoldierDossiers\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SoldierDossierResource extends JsonResource
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}

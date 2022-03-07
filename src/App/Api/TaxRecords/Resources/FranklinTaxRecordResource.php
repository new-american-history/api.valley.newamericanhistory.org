<?php

namespace App\Api\TaxRecords\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FranklinTaxRecordResource extends JsonResource
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}

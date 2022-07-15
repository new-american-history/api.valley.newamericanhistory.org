<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\Censuses\Models\ManufacturingCensusProduct;

class ManufacturingCensusProductTypeController
{
    public function index(Request $request)
    {
        return ManufacturingCensusProduct::groupBy('type')
            ->whereNotNull('type')
            ->orderBy('type')
            ->get()
            ->pluck('type')
            ->map(function ($type) {
                return [
                    'value' => preg_replace('/, (et|&)c\.?$/i', '', $type),
                    'label' => $type,
                ];
            })
            ->toArray();
    }
}

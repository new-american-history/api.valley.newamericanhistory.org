<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\Censuses\Models\ManufacturingCensus;

class ManufacturingCensusBusinessController
{
    public function index(Request $request)
    {
        return ManufacturingCensus::groupBy('business')
            ->whereNotNull('business')
            ->orderBy('business')
            ->get()
            ->pluck('business')
            ->map(function ($business) {
                return [
                    'value' => $business,
                    'label' => $business,
                ];
            })
            ->toArray();
    }
}

<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\Censuses\Models\ManufacturingCensus;

class ManufacturingCensusLocationController
{
    public function index(Request $request)
    {
        return ManufacturingCensus::groupBy('location')
            ->whereNotNull('location')
            ->orderBy('location')
            ->get()
            ->pluck('location')
            ->map(function ($location) {
                return [
                    'value' => $location,
                    'label' => $location,
                ];
            })
            ->toArray();
    }
}

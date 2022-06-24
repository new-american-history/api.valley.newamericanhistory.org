<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\Censuses\Models\VeteranCensus;

class VeteranCensusLocationController
{
    public function index(Request $request)
    {
        return VeteranCensus::groupBy('location')
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

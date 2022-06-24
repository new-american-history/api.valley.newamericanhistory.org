<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\Censuses\Models\VeteranCensus;

class VeteranCensusRegimentController
{
    public function index(Request $request)
    {
        return VeteranCensus::groupBy('regiment')
            ->whereNotNull('regiment')
            ->orderBy('regiment')
            ->get()
            ->pluck('regiment')
            ->map(function ($regiment) {
                return [
                    'value' => $regiment,
                    'label' => $regiment,
                ];
            })
            ->toArray();
    }
}

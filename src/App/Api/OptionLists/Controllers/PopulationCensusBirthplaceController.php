<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\Censuses\Models\PopulationCensus;

class PopulationCensusBirthplaceController
{
    public function index(Request $request)
    {
        return PopulationCensus::groupBy('birthplace')
            ->whereNotNull('birthplace')
            ->orderBy('birthplace')
            ->get()
            ->pluck('birthplace')
            ->map(function ($birthplace) {
                return [
                    'value' => $birthplace,
                    'label' => $birthplace,
                ];
            })
            ->toArray();
    }
}

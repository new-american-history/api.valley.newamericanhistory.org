<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\Censuses\Models\VeteranCensus;

class VeteranCensusRankController
{
    public function index(Request $request)
    {
        return VeteranCensus::groupBy('rank')
            ->whereNotNull('rank')
            ->orderBy('rank')
            ->get()
            ->pluck('rank')
            ->map(function ($rank) {
                return [
                    'value' => $rank,
                    'label' => $rank,
                ];
            })
            ->toArray();
    }
}

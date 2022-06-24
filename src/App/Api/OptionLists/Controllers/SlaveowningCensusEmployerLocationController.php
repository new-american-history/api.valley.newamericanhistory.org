<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\Censuses\Models\SlaveowningCensus;

class SlaveowningCensusEmployerLocationController
{
    public function index(Request $request)
    {
        return SlaveowningCensus::groupBy('employer_location')
            ->whereNotNull('employer_location')
            ->orderBy('employer_location')
            ->get()
            ->pluck('employer_location')
            ->map(function ($employerLocation) {
                return [
                    'value' => $employerLocation,
                    'label' => $employerLocation,
                ];
            })
            ->toArray();
    }
}

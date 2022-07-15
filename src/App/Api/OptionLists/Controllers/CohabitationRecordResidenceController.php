<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\CohabitationRecords\Models\Family;

class CohabitationRecordResidenceController
{
    public function index(Request $request)
    {
        return Family::groupBy('residence')
            ->whereNotNull('residence')
            ->orderBy('residence')
            ->get()
            ->pluck('residence')
            ->map(function ($residence) {
                return [
                    'value' => preg_replace('/, .+/', '', $residence),
                    'label' => $residence,
                ];
            })
            ->toArray();
    }
}

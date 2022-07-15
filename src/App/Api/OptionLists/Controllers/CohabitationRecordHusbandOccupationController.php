<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\CohabitationRecords\Models\Family;

class CohabitationRecordHusbandOccupationController
{
    public function index(Request $request)
    {
        return Family::groupBy('husband_occupation')
            ->whereNotNull('husband_occupation')
            ->orderBy('husband_occupation')
            ->get()
            ->pluck('husband_occupation')
            ->map(function ($occupation) {
                return [
                    'value' => $occupation,
                    'label' => $occupation,
                ];
            })
            ->toArray();
    }
}

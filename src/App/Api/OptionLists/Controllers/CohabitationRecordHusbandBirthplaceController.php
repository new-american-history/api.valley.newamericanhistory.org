<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\CohabitationRecords\Models\Family;

class CohabitationRecordHusbandBirthplaceController
{
    public function index(Request $request)
    {
        return Family::groupBy('husband_birthplace')
            ->whereNotNull('husband_birthplace')
            ->orderBy('husband_birthplace')
            ->get()
            ->pluck('husband_birthplace')
            ->map(function ($birthplace) {
                return [
                    'value' => preg_replace('/, .+/', '', $birthplace),
                    'label' => $birthplace,
                ];
            })
            ->toArray();
    }
}

<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\CohabitationRecords\Models\Family;

class CohabitationRecordWifeBirthplaceController
{
    public function index(Request $request)
    {
        return Family::groupBy('wife_birthplace')
            ->whereNotNull('wife_birthplace')
            ->orderBy('wife_birthplace')
            ->get()
            ->pluck('wife_birthplace')
            ->map(function ($birthplace) {
                return [
                    'value' => $birthplace,
                    'label' => $birthplace,
                ];
            })
            ->toArray();
    }
}

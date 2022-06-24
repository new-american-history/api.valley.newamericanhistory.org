<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\TaxRecords\Models\FranklinTaxRecord;

class FranklinTaxRecordOccupationController
{
    public function index(Request $request)
    {
        return FranklinTaxRecord::groupBy('occupation')
            ->whereNotNull('occupation')
            ->orderBy('occupation')
            ->get()
            ->pluck('occupation')
            ->map(function ($occupation) {
                return [
                    'value' => $occupation,
                    'label' => $occupation,
                ];
            })
            ->toArray();
    }
}

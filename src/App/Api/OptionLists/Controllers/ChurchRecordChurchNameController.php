<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\ChurchRecords\Models\ChurchRecord;

class ChurchRecordChurchNameController
{
    public function index(Request $request)
    {
        return ChurchRecord::groupBy('church_name')
            ->whereNotNull('church_name')
            ->orderBy('church_name')
            ->get()
            ->pluck('church_name')
            ->map(function ($churchName) {
                return [
                    'value' => $churchName,
                    'label' => $churchName,
                ];
            })
            ->toArray();
    }
}

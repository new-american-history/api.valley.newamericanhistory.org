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
            ->map(function ($churchRecord) {
                return [
                    'value' => $churchRecord->church_name,
                    'label' => $churchRecord->church_name,
                    'county' => $churchRecord->county,
                    'county_label' => $churchRecord->county_label,
                ];
            })
            ->toArray();
    }
}

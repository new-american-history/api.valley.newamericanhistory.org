<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\Censuses\Models\VeteranCensus;

class VeteranCensusCompanyController
{
    public function index(Request $request)
    {
        return VeteranCensus::groupBy('company')
            ->whereNotNull('company')
            ->orderBy('company')
            ->get()
            ->pluck('company')
            ->map(function ($company) {
                return [
                    'value' => $company,
                    'label' => $company,
                ];
            })
            ->toArray();
    }
}

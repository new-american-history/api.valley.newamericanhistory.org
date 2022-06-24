<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\SoldierDossiers\Models\SoldierDossier;

class SoldierDossierCompanyController
{
    public function index(Request $request)
    {
        return SoldierDossier::groupBy('company')
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

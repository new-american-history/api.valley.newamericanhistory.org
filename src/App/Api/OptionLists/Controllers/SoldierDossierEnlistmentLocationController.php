<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\SoldierDossiers\Models\SoldierDossier;

class SoldierDossierEnlistmentLocationController
{
    public function index(Request $request)
    {
        return SoldierDossier::groupBy('enlisted_location')
            ->whereNotNull('enlisted_location')
            ->orderBy('enlisted_location')
            ->get()
            ->pluck('enlisted_location')
            ->map(function ($enlistedLocation) {
                return [
                    'value' => $enlistedLocation,
                    'label' => $enlistedLocation,
                ];
            })
            ->toArray();
    }
}

<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\SoldierDossiers\Models\SoldierDossier;

class SoldierDossierEnlistmentOccupationController
{
    public function index(Request $request)
    {
        return SoldierDossier::groupBy('enlisted_occupation')
            ->whereNotNull('enlisted_occupation')
            ->orderBy('enlisted_occupation')
            ->get()
            ->pluck('enlisted_occupation')
            ->map(function ($enlistedOccupation) {
                return [
                    'value' => $enlistedOccupation,
                    'label' => $enlistedOccupation,
                ];
            })
            ->toArray();
    }
}

<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\SoldierDossiers\Models\SoldierDossier;

class SoldierDossierEnlistmentRankController
{
    public function index(Request $request)
    {
        return SoldierDossier::groupBy('enlisted_rank')
            ->whereNotNull('enlisted_rank')
            ->orderBy('enlisted_rank')
            ->get()
            ->pluck('enlisted_rank')
            ->map(function ($enlistedRank) {
                return [
                    'value' => $enlistedRank,
                    'label' => $enlistedRank,
                ];
            })
            ->toArray();
    }
}

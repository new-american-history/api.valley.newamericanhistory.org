<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\SoldierDossiers\Models\SoldierDossier;

class SoldierDossierRegimentController
{
    public function index(Request $request)
    {
        return SoldierDossier::groupBy('regiment')
            ->whereNotNull('regiment')
            ->orderBy('regiment')
            ->get()
            ->pluck('regiment')
            ->map(function ($regiment) {
                return [
                    'value' => $regiment,
                    'value' => preg_replace('/, C\.S\.A\./', '', $regiment),
                    'label' => $regiment,
                ];
            })
            ->toArray();
    }
}

<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\RegimentalMovements\Models\RegimentalMovement;

class RegimentalMovementCommanderController
{
    public function index(Request $request)
    {
        return RegimentalMovement::groupBy('commander')
            ->whereNotNull('commander')
            ->orderBy('commander')
            ->get()
            ->pluck('commander')
            ->map(function ($commander) {
                return [
                    'value' => $commander,
                    'label' => $commander,
                ];
            })
            ->toArray();
    }
}

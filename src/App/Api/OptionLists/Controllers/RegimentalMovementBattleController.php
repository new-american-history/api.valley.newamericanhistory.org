<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\RegimentalMovements\Models\RegimentalMovement;

class RegimentalMovementBattleController
{
    public function index(Request $request)
    {
        return RegimentalMovement::groupBy('battle_name')
            ->whereNotNull('battle_name')
            ->orderBy('battle_name')
            ->get()
            ->pluck('battle_name')
            ->map(function ($battleName) {
                return [
                    'value' => $battleName,
                    'label' => $battleName,
                ];
            })
            ->toArray();
    }
}

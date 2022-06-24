<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\RegimentalMovements\Models\RegimentalMovement;

class RegimentalMovementBrigadeController
{
    public function index(Request $request)
    {
        return RegimentalMovement::groupBy('brigade')
            ->whereNotNull('brigade')
            ->orderBy('brigade')
            ->get()
            ->pluck('brigade')
            ->map(function ($brigade) {
                return [
                    'value' => $brigade,
                    'label' => $brigade,
                ];
            })
            ->toArray();
    }
}

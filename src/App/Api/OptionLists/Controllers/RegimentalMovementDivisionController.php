<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\RegimentalMovements\Models\RegimentalMovement;

class RegimentalMovementDivisionController
{
    public function index(Request $request)
    {
        return RegimentalMovement::groupBy('division')
            ->whereNotNull('division')
            ->orderBy('division')
            ->get()
            ->pluck('division')
            ->map(function ($division) {
                return [
                    'value' => $division,
                    'label' => $division,
                ];
            })
            ->toArray();
    }
}

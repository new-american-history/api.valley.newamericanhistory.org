<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\RegimentalMovements\Models\RegimentalMovement;

class RegimentalMovementCorpsController
{
    public function index(Request $request)
    {
        return RegimentalMovement::groupBy('corps')
            ->whereNotNull('corps')
            ->orderBy('corps')
            ->get()
            ->pluck('corps')
            ->map(function ($corps) {
                return [
                    'value' => $corps,
                    'label' => $corps,
                ];
            })
            ->toArray();
    }
}

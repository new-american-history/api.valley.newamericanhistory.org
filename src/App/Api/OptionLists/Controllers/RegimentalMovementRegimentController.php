<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\RegimentalMovements\Models\Regiment;

class RegimentalMovementRegimentController
{
    public function index(Request $request)
    {
        return Regiment::orderBy('name')
            ->get()
            ->pluck('name')
            ->map(function ($regiment) {
                return [
                    'value' => $regiment,
                    'label' => $regiment,
                ];
            })
            ->toArray();
    }
}

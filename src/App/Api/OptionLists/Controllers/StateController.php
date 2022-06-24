<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\Shared\Enums\State;

class StateController
{
    public function index(Request $request)
    {
        return collect(State::toArray())
            ->map(function ($label, $key) {
                return [
                    'value' => $key,
                    'label' => $label,
                ];
            })
            ->values()
            ->toArray();
    }
}

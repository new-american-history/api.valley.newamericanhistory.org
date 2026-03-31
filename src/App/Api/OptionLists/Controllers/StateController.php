<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\Shared\Enums\State;

class StateController
{
    public function index(Request $request)
    {
        return collect(State::cases())
            ->map(fn (State $state) => [
                'value' => $state->value,
                'label' => $state->label(),
            ])
            ->values()
            ->toArray();
    }
}

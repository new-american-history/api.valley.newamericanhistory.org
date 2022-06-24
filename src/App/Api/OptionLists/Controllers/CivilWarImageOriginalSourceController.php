<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\CivilWarImages\Enums\OriginalSource;

class CivilWarImageOriginalSourceController
{
    public function index(Request $request)
    {
        return collect(OriginalSource::toArray())
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

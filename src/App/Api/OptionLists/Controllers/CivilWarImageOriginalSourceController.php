<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\CivilWarImages\Enums\OriginalSource;

class CivilWarImageOriginalSourceController
{
    public function index(Request $request)
    {
        return collect(OriginalSource::cases())
            ->map(fn (OriginalSource $source) => [
                'value' => $source->value,
                'label' => $source->label(),
            ])
            ->values()
            ->toArray();
    }
}

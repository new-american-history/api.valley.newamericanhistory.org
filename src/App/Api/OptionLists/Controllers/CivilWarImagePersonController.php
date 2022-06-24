<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\CivilWarImages\Models\Image;

class CivilWarImagePersonController
{
    public function index(Request $request)
    {
        return Image::groupBy('person_name')
            ->whereNotNull('person_name')
            ->orderBy('person_name')
            ->get()
            ->pluck('person_name')
            ->map(function ($person) {
                return [
                    'value' => $person,
                    'label' => $person,
                ];
            })
            ->toArray();
    }
}

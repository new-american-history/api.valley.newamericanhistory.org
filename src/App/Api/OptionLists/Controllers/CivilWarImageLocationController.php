<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\CivilWarImages\Models\Image;

class CivilWarImageLocationController
{
    public function index(Request $request)
    {
        return Image::groupBy('location')
            ->whereNotNull('location')
            ->orderBy('location')
            ->get()
            ->pluck('location')
            ->map(function ($location) {
                return [
                    'value' => $location,
                    'label' => $location,
                ];
            })
            ->toArray();
    }
}

<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\CivilWarImages\Models\Subject;

class CivilWarImageSubjectController
{
    public function index(Request $request)
    {
        return Subject::orderBy('name')
            ->get()
            ->pluck('name')
            ->map(function ($subject) {
                return [
                    'value' => $subject,
                    'label' => $subject,
                ];
            })
            ->toArray();
    }
}

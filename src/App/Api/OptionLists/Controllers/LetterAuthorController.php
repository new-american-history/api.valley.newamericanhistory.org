<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\Papers\Models\Letter;

class LetterAuthorController
{
    public function index(Request $request)
    {
        return Letter::groupBy('author')
            ->whereNotNull('author')
            ->orderBy('author')
            ->get()
            ->pluck('author')
            ->map(function ($author) {
                return [
                    'value' => $author,
                    'label' => $author,
                ];
            })
            ->toArray();
    }
}

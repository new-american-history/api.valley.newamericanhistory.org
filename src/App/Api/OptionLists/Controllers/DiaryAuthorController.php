<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;
use Domain\Papers\Models\Diary;

class DiaryAuthorController
{
    public function index(Request $request)
    {
        return Diary::groupBy('author')
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

<?php

namespace App\Api\OptionLists\Controllers;

use Illuminate\Http\Request;

class LetterKeywordController
{
    public function index(Request $request)
    {
        return [
            [
                'value' => 'African Americans',
                'label' => 'African Americans',
            ],
            [
                'value' => 'Agriculture',
                'label' => 'Agriculture',
            ],
            [
                'value' => 'Leisure',
                'label' => 'Arts and Leisure',
            ],
            [
                'value' => 'Battle Description',
                'label' => 'Battle Description',
            ],
            [
                'value' => 'Camp Life',
                'label' => 'Camp Life',
            ],
            [
                'value' => 'Religious Activity',
                'label' => 'Church and Religious Activity',
            ],
            [
                'value' => 'Casualties',
                'label' => 'Death and Casualties',
            ],
            [
                'value' => 'Desertion',
                'label' => 'Desertion or Leave',
            ],
            [
                'value' => 'Enlistment',
                'label' => 'Enlistment',
            ],
            [
                'value' => 'Family',
                'label' => 'Family',
            ],
            [
                'value' => 'Home Front',
                'label' => 'Home Front',
            ],
            [
                'value' => 'Hospitals',
                'label' => 'Medicine and Hospitals',
            ],
            [
                'value' => 'Military Authority',
                'label' => 'Military Authority',
            ],
            [
                'value' => 'Military Strategy',
                'label' => 'Military Strategy',
            ],
            [
                'value' => 'National Government',
                'label' => 'National Government',
            ],
            [
                'value' => 'Patriotism',
                'label' => 'Patriotism',
            ],
            [
                'value' => 'Prisoners',
                'label' => 'Prisons and Prisoners',
            ],
            [
                'value' => 'Race Relations',
                'label' => 'Race Relations',
            ],
            [
                'value' => 'Slavery',
                'label' => 'Slavery',
            ],
            [
                'value' => 'State Government',
                'label' => 'State Government',
            ],
            [
                'value' => 'Troop Movement',
                'label' => 'Troop Movement',
            ],
            [
                'value' => 'Women',
                'label' => 'Women',
            ],
        ];
    }
}

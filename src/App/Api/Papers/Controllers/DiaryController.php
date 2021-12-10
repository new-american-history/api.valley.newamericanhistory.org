<?php

namespace App\Api\Papers\Controllers;

use Illuminate\Http\Request;
use Domain\Papers\Models\Diary;

class DiaryController
{
    public function index() {
        return Diary::all();
    }
}

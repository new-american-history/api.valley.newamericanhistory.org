<?php

namespace App\Api\Papers\Controllers;

use Illuminate\Http\Request;
use Domain\Papers\Models\Letter;

class LetterController
{
    public function index() {
        return Letter::all();
    }
}

<?php

namespace App\Api\Newspapers\Controllers;

use Illuminate\Http\Request;
use Domain\Newspapers\Models\Newspaper;

class NewspaperController
{
    public function index() {
        return Newspaper::all();
    }
}

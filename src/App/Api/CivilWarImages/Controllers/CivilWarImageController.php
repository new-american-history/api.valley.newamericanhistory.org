<?php

namespace App\Api\CivilWarImages\Controllers;

use Illuminate\Http\Request;
use Domain\CivilWarImages\Models\Image;

class CivilWarImageController
{
    public function index() {
        return Image::all();
    }
}

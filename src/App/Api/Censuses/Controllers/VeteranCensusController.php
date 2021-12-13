<?php

namespace App\Api\Censuses\Controllers;

use Illuminate\Http\Request;
use Domain\Censuses\Models\VeteranCensus;

class VeteranCensusController
{
    public function index() {
        return VeteranCensus::all();
    }
}

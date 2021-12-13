<?php

namespace App\Api\Censuses\Controllers;

use Illuminate\Http\Request;
use Domain\Censuses\Models\ManufacturingCensus;

class ManufacturingCensusController
{
    public function index() {
        return ManufacturingCensus::all();
    }
}

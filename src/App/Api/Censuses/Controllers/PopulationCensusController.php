<?php

namespace App\Api\Censuses\Controllers;

use Illuminate\Http\Request;
use Domain\Censuses\Models\PopulationCensus;

class PopulationCensusController
{
    public function index() {
        return PopulationCensus::all();
    }
}

<?php

namespace App\Api\Censuses\Controllers;

use Illuminate\Http\Request;
use Domain\Censuses\Models\AgriculturalCensus;

class AgriculturalCensusController
{
    public function index() {
        return AgriculturalCensus::all();
    }
}

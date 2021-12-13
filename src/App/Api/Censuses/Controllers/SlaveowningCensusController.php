<?php

namespace App\Api\Censuses\Controllers;

use Illuminate\Http\Request;
use Domain\Censuses\Models\SlaveowningCensus;

class SlaveowningCensusController
{
    public function index() {
        return SlaveowningCensus::all();
    }
}

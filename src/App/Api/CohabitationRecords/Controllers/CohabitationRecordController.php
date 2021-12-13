<?php

namespace App\Api\CohabitationRecords\Controllers;

use Illuminate\Http\Request;
use Domain\CohabitationRecords\Models\Family;

class CohabitationRecordController
{
    public function index() {
        return Family::all();
    }
}

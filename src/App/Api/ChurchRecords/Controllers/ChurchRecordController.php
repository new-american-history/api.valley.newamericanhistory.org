<?php

namespace App\Api\ChurchRecords\Controllers;

use Illuminate\Http\Request;
use Domain\ChurchRecords\Models\ChurchRecord;

class ChurchRecordController
{
    public function index() {
        return ChurchRecord::all();
    }
}

<?php

namespace App\Api\TaxRecords\Controllers;

use Illuminate\Http\Request;
use Domain\TaxRecords\Models\AugustaTaxRecord;

class AugustaTaxRecordController
{
    public function index() {
        return AugustaTaxRecord::all();
    }
}

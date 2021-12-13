<?php

namespace App\Api\TaxRecords\Controllers;

use Illuminate\Http\Request;
use Domain\TaxRecords\Models\FranklinTaxRecord;

class FranklinTaxRecordController
{
    public function index() {
        return FranklinTaxRecord::all();
    }
}

<?php

namespace App\Api\Claims\Controllers;

use Illuminate\Http\Request;
use Domain\Claims\Models\ChambersburgClaim;

class ChambersburgClaimController
{
    public function index() {
        return ChambersburgClaim::all();
    }
}

<?php

namespace App\Api\Claims\Controllers;

use Illuminate\Http\Request;
use Domain\Claims\Models\SouthernClaimsCommissionClaim;

class SouthernClaimsCommissionController
{
    public function index() {
        return SouthernClaimsCommissionClaim::all();
    }
}

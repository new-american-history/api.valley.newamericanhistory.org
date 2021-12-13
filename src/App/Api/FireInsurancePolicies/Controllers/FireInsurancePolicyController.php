<?php

namespace App\Api\FireInsurancePolicies\Controllers;

use Illuminate\Http\Request;
use Domain\FireInsurancePolicies\Models\FireInsurancePolicy;

class FireInsurancePolicyController
{
    public function index() {
        return FireInsurancePolicy::all();
    }
}

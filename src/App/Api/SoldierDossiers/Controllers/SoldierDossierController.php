<?php

namespace App\Api\SoldierDossiers\Controllers;

use Illuminate\Http\Request;
use Domain\SoldierDossiers\Models\SoldierDossier;

class SoldierDossierController
{
    public function index() {
        return SoldierDossier::all();
    }
}

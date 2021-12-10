<?php

namespace App\Api\BattlefieldCorrespondence\Controllers;

use Illuminate\Http\Request;
use Domain\BattlefieldCorrespondence\Models\BattlefieldCorrespondence;

class BattlefieldCorrespondenceController
{
    public function index() {
        return BattlefieldCorrespondence::all();
    }
}

<?php

namespace App\Api\RegimentalMovements\Controllers;

use Illuminate\Http\Request;
use Domain\RegimentalMovements\Models\RegimentalMovement;

class RegimentalMovementController
{
    public function index() {
        return RegimentalMovement::all();
    }
}

<?php

namespace App\Api\FreeBlackRegistry\Controllers;

use Illuminate\Http\Request;
use Domain\FreeBlackRegistry\Models\FreeBlackRegistry;

class FreeBlackRegistryController
{
    public function index() {
        return FreeBlackRegistry::all();
    }
}

<?php

namespace App\Api\RegimentalMovements\Queries;

use Illuminate\Http\Request;
use Support\Queries\IndexQueryBuilder;
use Domain\RegimentalMovements\Models\RegimentalMovement;

class RegimentalMovementIndexQuery extends IndexQueryBuilder
{
    public function __construct(Request $request)
    {
        $query = RegimentalMovement::query()->with('regiment');

        parent::__construct($query, $request);

        $this->allowedFilters($this->mapAllowedFilters(RegimentalMovement::class));
        $this->allowedSorts($this->mapAllowedSorts(RegimentalMovement::class));
        $this->defaultSort('battle_start_date');
    }
}

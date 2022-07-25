<?php

namespace App\Api\SoldierDossiers\Queries;

use Illuminate\Http\Request;
use Support\Queries\IndexQueryBuilder;
use Domain\SoldierDossiers\Models\SoldierDossier;

class SoldierDossierIndexQuery extends IndexQueryBuilder
{
    public function __construct(Request $request)
    {
        $query = SoldierDossier::query()->with('images');

        parent::__construct($query, $request);

        $this->allowedFilters($this->mapAllowedFilters(SoldierDossier::class));
        $this->allowedSorts($this->mapAllowedSorts(SoldierDossier::class));
        $this->defaultSort('last_name');
    }
}

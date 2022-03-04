<?php

namespace App\Api\SoldierDossiers\Queries;

use Illuminate\Http\Request;
use Support\Queries\IndexQueryBuilder;
use Domain\SoldierDossiers\Models\SoldierDossier;

class SoldierDossierIndexQuery extends IndexQueryBuilder
{
    public function __construct(Request $request)
    {
        $query = SoldierDossier::query();

        parent::__construct($query, $request);

        $this->allowedFilters($this->mapAllowedFilters(SoldierDossier::class));
        $this->defaultSort('last_name');
    }
}

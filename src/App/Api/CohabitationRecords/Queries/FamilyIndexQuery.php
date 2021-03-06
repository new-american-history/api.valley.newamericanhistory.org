<?php

namespace App\Api\CohabitationRecords\Queries;

use Illuminate\Http\Request;
use Support\Queries\IndexQueryBuilder;
use Domain\CohabitationRecords\Models\Family;

class FamilyIndexQuery extends IndexQueryBuilder
{
    public function __construct(Request $request)
    {
        $query = Family::query()->with('children');

        parent::__construct($query, $request);

        $this->allowedFilters($this->mapAllowedFilters(Family::class));
        $this->allowedSorts($this->mapAllowedSorts(Family::class));
        $this->defaultSort('husband_last_name');
    }
}

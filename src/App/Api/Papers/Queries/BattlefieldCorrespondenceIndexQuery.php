<?php

namespace App\Api\Papers\Queries;

use Illuminate\Http\Request;
use Support\Queries\IndexQueryBuilder;
use Domain\BattlefieldCorrespondence\Models\BattlefieldCorrespondence;

class BattlefieldCorrespondenceIndexQuery extends IndexQueryBuilder
{
    public function __construct(Request $request)
    {
        $query = BattlefieldCorrespondence::query()->with(['notes']);

        parent::__construct($query, $request);

        $this->allowedFilters($this->mapAllowedFilters(BattlefieldCorrespondence::class));
        $this->allowedSorts($this->mapAllowedSorts(BattlefieldCorrespondence::class));
        $this->defaultSort('date');
    }
}

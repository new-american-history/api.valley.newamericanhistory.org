<?php

namespace App\Api\ChurchRecords\Queries;

use Illuminate\Http\Request;
use Support\Queries\IndexQueryBuilder;
use Domain\ChurchRecords\Models\ChurchRecord;

class ChurchRecordIndexQuery extends IndexQueryBuilder
{
    public function __construct(Request $request)
    {
        $query = ChurchRecord::query();

        parent::__construct($query, $request);

        $this->allowedFilters($this->mapAllowedFilters(ChurchRecord::class));
        $this->allowedSorts($this->mapAllowedSorts(ChurchRecord::class));
        $this->defaultSort('date');
    }
}

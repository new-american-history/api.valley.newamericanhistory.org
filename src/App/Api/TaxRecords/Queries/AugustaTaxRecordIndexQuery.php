<?php

namespace App\Api\TaxRecords\Queries;

use Illuminate\Http\Request;
use Support\Queries\IndexQueryBuilder;
use Domain\TaxRecords\Models\AugustaTaxRecord;

class AugustaTaxRecordIndexQuery extends IndexQueryBuilder
{
    public function __construct(Request $request)
    {
        $query = AugustaTaxRecord::query();

        parent::__construct($query, $request);

        $this->allowedFilters($this->mapAllowedFilters(AugustaTaxRecord::class));
        $this->allowedSorts($this->mapAllowedSorts(AugustaTaxRecord::class));
        $this->defaultSort('last_name');
    }
}

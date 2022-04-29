<?php

namespace App\Api\TaxRecords\Queries;

use Illuminate\Http\Request;
use Support\Queries\IndexQueryBuilder;
use Domain\TaxRecords\Models\FranklinTaxRecord;

class FranklinTaxRecordIndexQuery extends IndexQueryBuilder
{
    public function __construct(Request $request)
    {
        $query = FranklinTaxRecord::query();

        parent::__construct($query, $request);

        $this->allowedFilters($this->mapAllowedFilters(FranklinTaxRecord::class));
        $this->defaultSort('last_name');
    }
}

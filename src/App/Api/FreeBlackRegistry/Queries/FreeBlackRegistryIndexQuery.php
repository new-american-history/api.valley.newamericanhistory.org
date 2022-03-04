<?php

namespace App\Api\FreeBlackRegistry\Queries;

use Illuminate\Http\Request;
use Support\Queries\IndexQueryBuilder;
use Domain\FreeBlackRegistry\Models\FreeBlackRegistry;

class FreeBlackRegistryIndexQuery extends IndexQueryBuilder
{
    public function __construct(Request $request)
    {
        $query = FreeBlackRegistry::query();

        parent::__construct($query, $request);

        $this->allowedFilters($this->mapAllowedFilters(FreeBlackRegistry::class));
        $this->defaultSort('id');
    }
}

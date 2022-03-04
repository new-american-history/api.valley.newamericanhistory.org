<?php

namespace App\Api\Papers\Queries;

use Illuminate\Http\Request;
use Domain\Papers\Models\Letter;
use Support\Queries\IndexQueryBuilder;

class LetterIndexQuery extends IndexQueryBuilder
{
    public function __construct(Request $request)
    {
        $query = Letter::query()->with(['images', 'notes']);

        parent::__construct($query, $request);

        $this->allowedFilters($this->mapAllowedFilters(Letter::class));
        $this->defaultSort('date');
    }
}

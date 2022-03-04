<?php

namespace App\Api\Papers\Queries;

use Illuminate\Http\Request;
use Domain\Papers\Models\Diary;
use Support\Queries\IndexQueryBuilder;

class DiaryIndexQuery extends IndexQueryBuilder
{
    public function __construct(Request $request)
    {
        $query = Diary::query()->with(['images', 'notes']);

        parent::__construct($query, $request);

        $this->allowedFilters($this->mapAllowedFilters(Diary::class));
        $this->defaultSort('start_date');
    }
}

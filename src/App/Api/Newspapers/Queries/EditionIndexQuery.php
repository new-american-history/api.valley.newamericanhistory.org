<?php

namespace App\Api\Newspapers\Queries;

use Illuminate\Http\Request;
use Domain\Newspapers\Models\Edition;
use Spatie\QueryBuilder\AllowedFilter;
use Support\Queries\IndexQueryBuilder;
use Illuminate\Database\Eloquent\Builder;

class EditionIndexQuery extends IndexQueryBuilder
{
    public function __construct(Request $request)
    {
        $query = Edition::query()->with('newspaper');

        parent::__construct($query, $request);

        $this->allowedFilters(
            array_merge(
                $this->mapAllowedFilters(Edition::class),
                [
                    AllowedFilter::callback('year', function (Builder $query, $value) {
                        $query->whereYear('date', $value);
                    })
                ]
            )
        );
        $this->allowedSorts($this->mapAllowedSorts(Edition::class));
        $this->defaultSort('date');
    }
}

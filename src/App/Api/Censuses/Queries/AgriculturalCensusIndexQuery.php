<?php

namespace App\Api\Censuses\Queries;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Domain\Censuses\Models\AgriculturalCensus;

class AgriculturalCensusIndexQuery extends QueryBuilder
{
    public function __construct(Request $request)
    {
        $query = AgriculturalCensus::query();

        parent::__construct($query, $request);

        $this->allowedFilters(array_merge(
            !empty(AgriculturalCensus::$fuzzyFilters)
                ? AgriculturalCensus::$fuzzyFilters
                : [],
            !empty(AgriculturalCensus::$exactFilters)
                ? array_map(function ($name) {
                    return AllowedFilter::exact($name);
                }, AgriculturalCensus::$exactFilters)
                : [],
            !empty(AgriculturalCensus::$numericFilters)
                ? array_reduce(AgriculturalCensus::$numericFilters, function ($l, $f) {
                    $l[] = AllowedFilter::exact($f);

                    $l[] = AllowedFilter::callback($f . ':gt', function ($query, $value) use ($f) {
                        $query->where($f, '>', $value);
                    });
                    $l[] = AllowedFilter::callback($f . ':gte', function ($query, $value) use ($f) {
                        $query->where($f, '>=', $value);
                    });

                    $l[] = AllowedFilter::callback($f . ':lte', function ($query, $value) use ($f) {
                        $query->where($f, '<=', $value);
                    });
                    $l[] = AllowedFilter::callback($f . ':lt', function ($query, $value) use ($f) {
                        $query->where($f, '<', $value);
                    });

                    return $l;
                }, [])
                : [],
        ));

        $this->defaultSort('last_name');
    }
}

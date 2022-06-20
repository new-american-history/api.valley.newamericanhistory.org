<?php

namespace Support\Queries;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class IndexQueryBuilder extends QueryBuilder
{
    public function __construct($query, Request $request)
    {
        parent::__construct($query, $request);
    }

    public function mapAllowedFilters($model)
    {
        return array_merge(
            !empty($model::$fuzzyFilters)
                ? $model::$fuzzyFilters
                : [],
            !empty($model::$exactFilters)
                ? array_map(function ($name) {
                    return AllowedFilter::exact($name);
                }, $model::$exactFilters)
                : [],
            !empty($model::$numericFilters)
                ? array_reduce($model::$numericFilters, function ($l, $f) {
                    $l[] = AllowedFilter::exact($f);

                    $l[] = AllowedFilter::callback($f . ':gt', function ($query, $value) use ($f) {
                        $query->where($f, '>', $value);
                    });
                    $l[] = AllowedFilter::callback($f . ':gte', function ($query, $value) use ($f) {
                        $query->where($f, '>=', $value);
                    });

                    $l[] = AllowedFilter::callback($f . ':ne', function ($query, $value) use ($f) {
                        $query->where($f, '!=', $value);
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
        );
    }

    public function mapAllowedSorts($model)
    {
        $fields = \Schema::getColumnListing((new $model)->getTable());
        $excludedSorts = $model::$excludedSorts ?? [];

        return array_map(function ($field) use ($excludedSorts) {
            if (!in_array($field, $excludedSorts)) {
                return AllowedSort::field($field);
            }
        }, $fields);
    }
}

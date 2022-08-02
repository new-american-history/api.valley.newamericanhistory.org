<?php

namespace Support\Queries;

use Illuminate\Http\Request;
use Support\Filters\DateFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use Support\Filters\TextSearchFilter;
use Spatie\QueryBuilder\AllowedFilter;
use Support\Filters\ExactFilterWithCommas;

class IndexQueryBuilder extends QueryBuilder
{
    public function __construct($query, Request $request)
    {
        parent::__construct($query, $request);
    }

    public function mapAllowedFilters($modelClass)
    {
        return array_merge(
            !empty($modelClass::$fuzzyFilters)
                ? $modelClass::$fuzzyFilters
                : [],
            !empty($modelClass::$exactFilters)
                ? array_map(function ($name) {
                    return AllowedFilter::exact($name);
                }, $modelClass::$exactFilters) : [],
            !empty($modelClass::$exactFiltersWithCommas)
                ? array_reduce($modelClass::$exactFiltersWithCommas, function ($l, $f) use ($modelClass) {
                    $l[] = AllowedFilter::custom($f, new ExactFilterWithCommas($f, $modelClass));
                    return $l;
                }, []) : [],
            !empty($modelClass::$numericFilters)
                ? array_reduce($modelClass::$numericFilters, function ($l, $f) {
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
                }, []) : [],

            !empty($modelClass::$dateFilters)
                ? array_reduce($modelClass::$dateFilters, function ($l, $f) use ($modelClass) {
                    $l[] = AllowedFilter::custom($f, new DateFilter($modelClass, $f, [
                        ['>=', 'start'], ['<=', 'end']
                    ]));

                    $l[] = AllowedFilter::custom($f . ':gt', new DateFilter($modelClass, $f, [
                        ['>', 'end']
                    ]));
                    $l[] = AllowedFilter::custom($f . ':gte',  new DateFilter($modelClass, $f, [
                        ['>=', 'start']
                    ]));

                    $l[] = AllowedFilter::custom($f . ':ne',  new DateFilter($modelClass, $f, [
                        ['<', 'start'], ['>', 'end']
                    ], false));

                    $l[] = AllowedFilter::custom($f . ':lte',  new DateFilter($modelClass, $f, [
                        ['<=', 'end']
                    ]));
                    $l[] = AllowedFilter::custom($f . ':lt',  new DateFilter($modelClass, $f, [
                        ['<', 'start']
                    ]));

                    return $l;
                }, []) : [],
            !empty($modelClass::$fuzzyFilters) || !empty($modelClass($exactFilters))
                ? [
                    AllowedFilter::custom('q', new TextSearchFilter(
                        array_merge(
                            !empty($modelClass::$fuzzyFilters) ? $modelClass::$fuzzyFilters : [],
                            !empty($modelClass::$exactFilters) ? $modelClass::$exactFilters : []
                        ),
                        $modelClass
                    ))
                ] : [],
        );
    }

    public function mapAllowedSorts($modelClass)
    {
        $fields = \Schema::getColumnListing((new $modelClass)->getTable());
        $excludedSorts = $modelClass::$excludedSorts ?? [];

        return array_map(function ($field) use ($excludedSorts) {
            if (!in_array($field, $excludedSorts)) {
                return AllowedSort::field($field);
            }
        }, $fields);
    }
}

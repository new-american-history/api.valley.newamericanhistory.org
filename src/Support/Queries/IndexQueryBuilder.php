<?php

namespace Support\Queries;

use Illuminate\Http\Request;
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
            !empty($model::$exactFiltersWithCommas)
                ? array_reduce($model::$exactFiltersWithCommas, function ($l, $f) use ($model) {
                    $l[] = AllowedFilter::custom($f, new ExactFilterWithCommas(
                        $model::$exactFiltersWithCommas,
                        $model
                    ));
                    return $l;
                }, [])
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
            !empty($model::$dateFilters)
                ? array_reduce($model::$dateFilters, function ($l, $f) {
                    $l[] = $this->getDateAllowedFilter($f, '', [
                        ['>=', 'start'], ['<=', 'end']
                    ], true);

                    $l[] = $this->getDateAllowedFilter($f, ':gt', [['>', 'end']]);
                    $l[] = $this->getDateAllowedFilter($f, ':gte', [['>=', 'start']]);

                    $l[] = $this->getDateAllowedFilter($f, ':ne', [
                        ['<', 'start'], ['>', 'end']
                    ], false);

                    $l[] = $this->getDateAllowedFilter($f, ':lte', [['<=', 'end']]);
                    $l[] = $this->getDateAllowedFilter($f, ':lt', [['<', 'start']]);

                    return $l;
                }, [])
                : [],
            !empty($model::$fuzzyFilters) || !empty($model($exactFilters))
                ? [
                    AllowedFilter::custom('q', new TextSearchFilter(
                        array_merge(
                            !empty($model::$fuzzyFilters) ? $model::$fuzzyFilters : [],
                            !empty($model::$exactFilters) ? $model::$exactFilters : []
                        ),
                        $model
                    ))
                ]
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

    protected function getDateAllowedFilter($f, $modifier, $conditions, $requireAllConditions = true)
    {
        return AllowedFilter::callback($f . $modifier, function ($query, $value) use ($f, $requireAllConditions, $conditions) {
            if ($requireAllConditions) {
                foreach ($conditions as $condition) {
                    $dateValue = $condition[1] === 'end' ? $this->getEndOfDate($value) : $this->getStartOfDate($value);
                    $query->where($f, $condition[0], $dateValue);
                }
            } else {
                $query->where(function ($query) use ($f, $value, $conditions) {
                    foreach ($conditions as $index => $condition) {
                        $dateValue = $condition[1] === 'end' ? $this->getEndOfDate($value) : $this->getStartOfDate($value);
                        $queryFunction = $index === 0 ? 'where' : 'orWhere';
                        $query->$queryFunction($f, $condition[0], $dateValue);
                    }
                });
            }
        });
    }

    protected function getStartOfDate($value)
    {
        if (strlen($value) === 4) {
            return $value . '-01-01';
        } elseif (strlen($value) === 7) {
            return $value . '-01';
        }
        return strtotime($value) ? date('Y-m-d', strtotime($value)) : $value;
    }

    protected function getEndOfDate($value)
    {
        if (strlen($value) === 4) {
            return $value . '-12-31';
        } elseif (strlen($value) === 7) {
            return $value . '-31';
        }
        return strtotime($value) ? date('Y-m-d', strtotime($value)) : $value;
    }
}

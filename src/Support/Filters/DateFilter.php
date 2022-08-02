<?php

namespace Support\Filters;

use Illuminate\Support\Collection;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\FiltersExact;

class DateFilter extends FiltersExact implements Filter
{
    protected string $initialModelClass;

    protected string $field;

    protected array $conditions;

    protected bool $requireAllConditions;


    public function __construct($initialModelClass, $field, $conditions, $requireAllConditions = true)
    {
        $this->initialModelClass = $initialModelClass;
        $this->field = $field;
        $this->conditions = $conditions;
        $this->requireAllConditions = $requireAllConditions;
    }

    public function __invoke(Builder $query, $value, string $property)
    // The $property parameter is unused but is a required part of the function declaration.
    {
        $currentModelClass = get_class($query->getModel());
        $isNestedClass = $currentModelClass != $this->initialModelClass;

        $query->where(function (Builder $query) use ($value, $isNestedClass): void {
            $field = $this->field;
            $wrappedProperty = null;

            if ($this->isRelationProperty($query, $field) && !$isNestedClass) {
                $this->withRelationConstraint($query, $value, $field);
            } else {
                if ($isNestedClass && str_contains($field, '.')) {
                    $table = $query->getModel()->getTable();
                    $parts = explode('.', $field);
                    $field = implode('.', [$table, end($parts)]);

                    if (
                        in_array($field, $this->relationConstraints) &&
                        end($this->relationConstraints) === $field
                    ) {
                        $wrappedProperty = $query->getQuery()->getGrammar()->wrap($query->qualifyColumn($field));
                    }
                } elseif (!$isNestedClass) {
                    $wrappedProperty = $query->getQuery()->getGrammar()->wrap($query->qualifyColumn($field));
                }

                if (!empty($wrappedProperty)) {
                    if (is_array($value) && count(array_filter($value, 'strlen')) != 0) {
                        // Don't allow array searches here (i.e., treat commas as commas, not value separators).
                        $value = implode(',', $value);
                    }

                    $unwrappedProperty = str_replace('`', '', $wrappedProperty);
                    $value = mb_strtolower($value, 'UTF8');

                    if ($this->requireAllConditions) {
                        foreach ($this->conditions as $condition) {
                            $dateValue = $condition[1] === 'end'
                                ? $this->getEndOfDate($value)
                                : $this->getStartOfDate($value);
                            $query->where($unwrappedProperty, $condition[0], $dateValue);
                        }
                    } else {
                        $query->where(function ($query) use ($unwrappedProperty, $value) {
                            foreach ($this->conditions as $index => $condition) {
                                $dateValue = $condition[1] === 'end'
                                    ? $this->getEndOfDate($value)
                                    : $this->getStartOfDate($value);
                                $queryFunction = $index === 0 ? 'where' : 'orWhere';
                                $query->$queryFunction($unwrappedProperty, $condition[0], $dateValue);
                            }
                        });
                    }
                }
            }
        });

        return;
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

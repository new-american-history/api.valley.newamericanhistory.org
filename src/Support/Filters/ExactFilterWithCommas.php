<?php

namespace Support\Filters;

use Illuminate\Support\Collection;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\FiltersExact;

class ExactFilterWithCommas extends FiltersExact implements Filter
{
    protected array $fields;
    protected string $initialModelClass;

    public function __construct($fields, $initialModelClass)
    {
        $this->fields = $fields;
        $this->initialModelClass = $initialModelClass;
    }

    public function __invoke(Builder $query, $value, string $property)
    // The $property parameter is unused but is a required part of the function declaration.
    {
        $currentModelClass = get_class($query->getModel());
        $isNestedClass = $currentModelClass != $this->initialModelClass;

        $query->where(function (Builder $query) use ($value, $isNestedClass): void {
            foreach ($this->fields as $field) {
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
                        $query->where($unwrappedProperty, $value);
                    }

                    // dd($query->toSql());
                }
            }
        });

        return;
    }
}

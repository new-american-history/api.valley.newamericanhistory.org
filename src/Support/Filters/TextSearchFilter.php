<?php

namespace Support\Filters;

use Illuminate\Support\Collection;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\FiltersExact;

class TextSearchFilter extends FiltersExact implements Filter
{
    protected array $fields;
    protected string $initialModelClass;

    public function __construct($fields, $initialModelClass)
    {
        $this->fields = $fields;
        $this->initialModelClass = $initialModelClass;
    }

    public function __invoke(Builder $query, $value, string $property)
    // The $property parameter is unused but is q requried part of the function declaration.
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
                        $sql = "{$wrappedProperty} LIKE ?";

                        if (is_array($value) && count(array_filter($value, 'strlen')) != 0) {
                            // Don't allow array searches here (i.e., treat commas as commas, not value separators),
                            // since many searches match body or other long text that may have commas included.
                            $value = implode(',', $value);
                        }

                        $value = mb_strtolower($value, 'UTF8');
                        $query->orWhereRaw($sql, ["%{$value}%"]);
                    }
                }
            }
        });

        return;
    }

    protected function withRelationConstraint(Builder $query, $value, string $property)
    // This function is pulled from FiltersExact, and adjusted to use orWhereHas() instead of whereHas().
    {
        [$relation, $property] = collect(explode('.', $property))
            ->pipe(function (Collection $parts) {
                return [
                    $parts->except(count($parts) - 1)->implode('.'),
                    $parts->last(),
                ];
            });

        $query->orWhereHas($relation, function (Builder $query) use ($value, $property) {
            $this->relationConstraints[] = $property = $query->qualifyColumn($property);

            $this->__invoke($query, $value, $property);
        });
    }
}

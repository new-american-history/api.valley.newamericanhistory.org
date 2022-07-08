<?php

namespace Support\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class TextSearchFilter implements Filter
{
    protected array $fields;

    public function __construct($fields)
    {
        $this->fields = $fields;
    }

    public function __invoke(Builder $builder, $values, string $property): Builder
    {
        $builder->where(function (Builder $builder) use ($values): void {
            foreach ($this->fields as $field) {
                $values = (array) $values;
                foreach ($values as $value) {
                    $builder->orWhere($field, 'LIKE', "%{$value}%");
                }
            }
        });

        return $builder;
    }
}

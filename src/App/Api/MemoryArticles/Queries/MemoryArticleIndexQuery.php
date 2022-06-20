<?php

namespace App\Api\MemoryArticles\Queries;

use Illuminate\Http\Request;
use Support\Queries\IndexQueryBuilder;
use Domain\MemoryArticles\Models\MemoryArticle;

class MemoryArticleIndexQuery extends IndexQueryBuilder
{
    public function __construct(Request $request)
    {
        $query = MemoryArticle::query();

        parent::__construct($query, $request);

        $this->allowedFilters($this->mapAllowedFilters(MemoryArticle::class));
        $this->allowedSorts($this->mapAllowedSorts(MemoryArticle::class));
        $this->defaultSort('date');
    }
}

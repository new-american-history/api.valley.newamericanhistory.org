<?php

namespace App\Api\MemoryArticles\Controllers;

use Illuminate\Http\Request;
use Domain\MemoryArticles\Models\MemoryArticle;
use App\Api\MemoryArticles\Queries\MemoryArticleIndexQuery;
use App\Api\MemoryArticles\Resources\MemoryArticleResource;

class MemoryArticleController
{
    public function index(MemoryArticleIndexQuery $query)
    {
        return MemoryArticleResource::collection(
            $query->paginate($request->perpage ?? 50)
        );
    }

    public function show(string $valley_id)
    {
        $memoryArticle = MemoryArticle::where(['valley_id' => $valley_id])->firstOrFail();
        return new MemoryArticleResource($memoryArticle);
    }
}

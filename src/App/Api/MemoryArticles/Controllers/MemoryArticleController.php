<?php

namespace App\Api\MemoryArticles\Controllers;

use Illuminate\Http\Request;
use Domain\MemoryArticles\Models\MemoryArticle;

class MemoryArticleController
{
    public function index() {
        return MemoryArticle::all();
    }
}

<?php

use App\Api\Papers\Controllers\DiaryController;
use App\Api\Papers\Controllers\LetterController;
use App\Api\Newspapers\Controllers\NewspaperController;
use App\Api\MemoryArticles\Controllers\MemoryArticleController;

Route::get('/diaries', [DiaryController::class, 'index']);
Route::get('/letters', [LetterController::class, 'index']);
Route::get('/memory-articles', [MemoryArticleController::class, 'index']);
Route::get('/newspapers', [NewspaperController::class, 'index']);

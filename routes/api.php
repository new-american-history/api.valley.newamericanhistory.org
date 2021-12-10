<?php

use App\Api\Papers\Controllers\DiaryController;
use App\Api\Papers\Controllers\LetterController;

Route::get('/diaries', [DiaryController::class, 'index']);
Route::get('/letters', [LetterController::class, 'index']);

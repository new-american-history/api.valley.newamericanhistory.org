<?php

use App\Api\Papers\Controllers\DiaryController;
use App\Api\Papers\Controllers\LetterController;
use App\Api\Newspapers\Controllers\NewspaperController;
use App\Api\Censuses\Controllers\VeteranCensusController;
use App\Api\Censuses\Controllers\PopulationCensusController;
use App\Api\Censuses\Controllers\SlaveOwningCensusController;
use App\Api\ChurchRecords\Controllers\ChurchRecordController;
use App\Api\Censuses\Controllers\AgriculturalCensusController;
use App\Api\TaxRecords\Controllers\AugustaTaxRecordController;
use App\Api\Censuses\Controllers\ManufacturingCensusController;
use App\Api\MemoryArticles\Controllers\MemoryArticleController;
use App\Api\TaxRecords\Controllers\FranklinTaxRecordController;
use App\Api\SoldierDossiers\Controllers\SoldierDossierController;
use App\Api\FreeBlackRegistry\Controllers\FreeBlackRegistryController;
use App\Api\CohabitationRecords\Controllers\CohabitationRecordController;
use App\Api\RegimentalMovements\Controllers\RegimentalMovementController;
use App\Api\BattlefieldCorrespondence\Controllers\BattlefieldCorrespondenceController;

Route::get('/agricultural-census', [AgriculturalCensusController::class, 'index']);
Route::get('/augusta-tax-records', [AugustaTaxRecordController::class, 'index']);
Route::get('/battlefield-correspondence', [BattlefieldCorrespondenceController::class, 'index']);
Route::get('/church-records', [ChurchRecordController::class, 'index']);
Route::get('/cohabitation-records', [CohabitationRecordController::class, 'index']);
Route::get('/diaries', [DiaryController::class, 'index']);
Route::get('/franklin-tax-records', [FranklinTaxRecordController::class, 'index']);
Route::get('/free-black-registry', [FreeBlackRegistryController::class, 'index']);
Route::get('/letters', [LetterController::class, 'index']);
Route::get('/manufacturing-census', [ManufacturingCensusController::class, 'index']);
Route::get('/memory-articles', [MemoryArticleController::class, 'index']);
Route::get('/newspapers', [NewspaperController::class, 'index']);
Route::get('/population-census', [PopulationCensusController::class, 'index']);
Route::get('/regimental-movements', [RegimentalMovementController::class, 'index']);
Route::get('/slaveowning-census', [SlaveOwningCensusController::class, 'index']);
Route::get('/soldier-dossiers', [SoldierDossierController::class, 'index']);
Route::get('/veteran-census', [VeteranCensusController::class, 'index']);

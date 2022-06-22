<?php

use App\Api\Papers\Controllers\DiaryController;
use App\Api\Papers\Controllers\LetterController;
use App\Api\Newspapers\Controllers\TopicController;
use App\Api\Newspapers\Controllers\EditionController;
use App\Api\Shared\Controllers\AutocompleteController;
use App\Api\Newspapers\Controllers\NewspaperController;
use App\Api\Censuses\Controllers\VeteranCensusController;
use App\Api\Claims\Controllers\ChambersburgClaimController;
use App\Api\Censuses\Controllers\PopulationCensusController;
use App\Api\Censuses\Controllers\SlaveowningCensusController;
use App\Api\ChurchRecords\Controllers\ChurchRecordController;
use App\Api\Censuses\Controllers\AgriculturalCensusController;
use App\Api\TaxRecords\Controllers\AugustaTaxRecordController;
use App\Api\Censuses\Controllers\ManufacturingCensusController;
use App\Api\CivilWarImages\Controllers\CivilWarImageController;
use App\Api\MemoryArticles\Controllers\MemoryArticleController;
use App\Api\TaxRecords\Controllers\FranklinTaxRecordController;
use App\Api\SoldierDossiers\Controllers\SoldierDossierController;
use App\Api\Claims\Controllers\SouthernClaimsCommissionController;
use App\Api\Papers\Controllers\BattlefieldCorrespondenceController;
use App\Api\FreeBlackRegistry\Controllers\FreeBlackRegistryController;
use App\Api\CohabitationRecords\Controllers\CohabitationRecordController;
use App\Api\RegimentalMovements\Controllers\RegimentalMovementController;
use App\Api\FireInsurancePolicies\Controllers\FireInsurancePolicyController;

// Shared

Route::get('autocomplete', [AutocompleteController::class, 'index']);

// Models

Route::get('/agricultural-census', [AgriculturalCensusController::class, 'index']);
Route::get('/augusta-tax-records', [AugustaTaxRecordController::class, 'index']);

Route::get('/battlefield-correspondence', [BattlefieldCorrespondenceController::class, 'index']);
Route::get('/battlefield-correspondence/{valley_id}', [BattlefieldCorrespondenceController::class, 'show']);

Route::get('/chambersburg-claims', [ChambersburgClaimController::class, 'index']);
Route::get('/church-records', [ChurchRecordController::class, 'index']);

Route::get('/civil-war-images', [CivilWarImageController::class, 'index']);
Route::get('/civil-war-images/{id}', [CivilWarImageController::class, 'show']);

Route::get('/cohabitation-records', [CohabitationRecordController::class, 'index']);

Route::get('/diaries', [DiaryController::class, 'index']);
Route::get('/diaries/{valley_id}', [DiaryController::class, 'show']);

Route::get('/fire-insurance-policies', [FireInsurancePolicyController::class, 'index']);
Route::get('/franklin-tax-records', [FranklinTaxRecordController::class, 'index']);
Route::get('/free-black-registry', [FreeBlackRegistryController::class, 'index']);

Route::get('/letters', [LetterController::class, 'index']);
Route::get('/letters/{valley_id}', [LetterController::class, 'show']);

Route::get('/manufacturing-census', [ManufacturingCensusController::class, 'index']);

Route::get('/memory-articles', [MemoryArticleController::class, 'index']);
Route::get('/memory-articles/{valley_id}', [MemoryArticleController::class, 'show']);

Route::get('/newspapers', [NewspaperController::class, 'index']);
Route::get('/newspapers/{slug}/editions/{year}/{month}/{day}', [NewspaperController::class, 'showEdition']);
Route::get('/newspaper-editions', [EditionController::class, 'index']);
Route::get('/newspaper-topics', [TopicController::class, 'index']);

Route::get('/population-census', [PopulationCensusController::class, 'index']);
Route::get('/regimental-movements', [RegimentalMovementController::class, 'index']);
Route::get('/slaveowning-census', [SlaveowningCensusController::class, 'index']);
Route::get('/soldier-dossiers', [SoldierDossierController::class, 'index']);

Route::get('/southern-claims-commission', [SouthernClaimsCommissionController::class, 'index']);
Route::get('/southern-claims-commission/{valley_id}', [SouthernClaimsCommissionController::class, 'show']);

Route::get('/veteran-census', [VeteranCensusController::class, 'index']);

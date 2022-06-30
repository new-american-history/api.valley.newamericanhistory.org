<?php

use App\Api\Papers\Controllers\DiaryController;
use App\Api\Papers\Controllers\LetterController;
use App\Api\Newspapers\Controllers\TopicController;
use App\Api\OptionLists\Controllers\StateController;
use App\Api\Newspapers\Controllers\EditionController;
use App\Api\Newspapers\Controllers\NewspaperController;
use App\Api\Censuses\Controllers\VeteranCensusController;
use App\Api\OptionLists\Controllers\DiaryAuthorController;
use App\Api\OptionLists\Controllers\DiaryKeywordController;
use App\Api\OptionLists\Controllers\LetterAuthorController;
use App\Api\Claims\Controllers\ChambersburgClaimController;
use App\Api\OptionLists\Controllers\LetterKeywordController;
use App\Api\Autocomplete\Controllers\AutocompleteController;
use App\Api\Censuses\Controllers\PopulationCensusController;
use App\Api\Censuses\Controllers\SlaveowningCensusController;
use App\Api\ChurchRecords\Controllers\ChurchRecordController;
use App\Api\Censuses\Controllers\AgriculturalCensusController;
use App\Api\TaxRecords\Controllers\AugustaTaxRecordController;
use App\Api\Censuses\Controllers\ManufacturingCensusController;
use App\Api\CivilWarImages\Controllers\CivilWarImageController;
use App\Api\MemoryArticles\Controllers\MemoryArticleController;
use App\Api\TaxRecords\Controllers\FranklinTaxRecordController;
use App\Api\OptionLists\Controllers\VeteranCensusRankController;
use App\Api\SoldierDossiers\Controllers\SoldierDossierController;
use App\Api\OptionLists\Controllers\CivilWarImagePersonController;
use App\Api\Claims\Controllers\SouthernClaimsCommissionController;
use App\Api\OptionLists\Controllers\CivilWarImageSubjectController;
use App\Api\Papers\Controllers\BattlefieldCorrespondenceController;
use App\Api\OptionLists\Controllers\CivilWarImageLocationController;
use App\Api\OptionLists\Controllers\SoldierDossierCompanyController;
use App\Api\OptionLists\Controllers\VeteranCensusLocationController;
use App\Api\OptionLists\Controllers\VeteranCensusRegimentController;
use App\Api\OptionLists\Controllers\SoldierDossierRegimentController;
use App\Api\OptionLists\Controllers\ChurchRecordChurchNameController;
use App\Api\OptionLists\Controllers\RegimentalMovementCorpsController;
use App\Api\FreeBlackRegistry\Controllers\FreeBlackRegistryController;
use App\Api\OptionLists\Controllers\RegimentalMovementBattleController;
use App\Api\OptionLists\Controllers\RegimentalMovementBrigadeController;
use App\Api\OptionLists\Controllers\PopulationCensusBirthplaceController;
use App\Api\OptionLists\Controllers\PopulationCensusOccupationController;
use App\Api\OptionLists\Controllers\RegimentalMovementDivisionController;
use App\Api\OptionLists\Controllers\RegimentalMovementRegimentController;
use App\Api\CohabitationRecords\Controllers\CohabitationRecordController;
use App\Api\RegimentalMovements\Controllers\RegimentalMovementController;
use App\Api\OptionLists\Controllers\CivilWarImageOriginalSourceController;
use App\Api\OptionLists\Controllers\FranklinTaxRecordOccupationController;
use App\Api\OptionLists\Controllers\ManufacturingCensusBusinessController;
use App\Api\OptionLists\Controllers\ManufacturingCensusLocationController;
use App\Api\OptionLists\Controllers\SoldierDossierEnlistmentRankController;
use App\Api\FireInsurancePolicies\Controllers\FireInsurancePolicyController;
use App\Api\OptionLists\Controllers\SoldierDossierEnlistmentLocationController;
use App\Api\OptionLists\Controllers\SlaveowningCensusEmployerLocationController;
use App\Api\OptionLists\Controllers\SoldierDossierEnlistmentOccupationController;

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
Route::get('/soldier-dossiers/{valley_id}', [SoldierDossierController::class, 'show']);
Route::get('/southern-claims-commission', [SouthernClaimsCommissionController::class, 'index']);
Route::get('/southern-claims-commission/{valley_id}', [SouthernClaimsCommissionController::class, 'show']);
Route::get('/veteran-census', [VeteranCensusController::class, 'index']);

// Autocomplete

Route::get('/autocomplete', [AutocompleteController::class, 'index']);

// Option lists

Route::group(['prefix' => 'option-lists'], function () {
    Route::get('/church-record-church-names', [ChurchRecordChurchNameController::class, 'index']);
    Route::get('/civil-war-image-locations', [CivilWarImageLocationController::class, 'index']);
    Route::get('/civil-war-image-original-sources', [CivilWarImageOriginalSourceController::class, 'index']);
    Route::get('/civil-war-image-people', [CivilWarImagePersonController::class, 'index']);
    Route::get('/civil-war-image-subjects', [CivilWarImageSubjectController::class, 'index']);
    Route::get('/diary-authors', [DiaryAuthorController::class, 'index']);
    Route::get('/diary-keywords', [DiaryKeywordController::class, 'index']);
    Route::get('/franklin-tax-record-occupations', [FranklinTaxRecordOccupationController::class, 'index']);
    Route::get('/letter-authors', [LetterAuthorController::class, 'index']);
    Route::get('/letter-keywords', [LetterKeywordController::class, 'index']);
    Route::get('/manufacturing-census-businesses', [ManufacturingCensusBusinessController::class, 'index']);
    Route::get('/manufacturing-census-locations', [ManufacturingCensusLocationController::class, 'index']);
    Route::get('/population-census-birthplaces', [PopulationCensusBirthplaceController::class, 'index']);
    Route::get('/population-census-occupations', [PopulationCensusOccupationController::class, 'index']);
    Route::get('/regimental-movement-battles', [RegimentalMovementBattleController::class, 'index']);
    Route::get('/regimental-movement-brigades', [RegimentalMovementBrigadeController::class, 'index']);
    Route::get('/regimental-movement-corps', [RegimentalMovementCorpsController::class, 'index']);
    Route::get('/regimental-movement-divisions', [RegimentalMovementDivisionController::class, 'index']);
    Route::get('/regimental-movement-regiments', [RegimentalMovementRegimentController::class, 'index']);
    Route::get('/slaveowning-census-employer-locations', [SlaveowningCensusEmployerLocationController::class, 'index']);
    Route::get('/soldier-dossier-companies', [SoldierDossierCompanyController::class, 'index']);
    Route::get('/soldier-dossier-enlistment-locations', [SoldierDossierEnlistmentLocationController::class, 'index']);
    Route::get('/soldier-dossier-enlistment-occupations', [SoldierDossierEnlistmentOccupationController::class, 'index']);
    Route::get('/soldier-dossier-enlistment-ranks', [SoldierDossierEnlistmentRankController::class, 'index']);
    Route::get('/soldier-dossier-regiments', [SoldierDossierRegimentController::class, 'index']);
    Route::get('/states', [StateController::class, 'index']);
    Route::get('/veteran-census-locations', [VeteranCensusLocationController::class, 'index']);
    Route::get('/veteran-census-ranks', [VeteranCensusRankController::class, 'index']);
    Route::get('/veteran-census-regiments', [VeteranCensusRegimentController::class, 'index']);
});

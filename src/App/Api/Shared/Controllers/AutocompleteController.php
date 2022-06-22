<?php

namespace App\Api\Shared\Controllers;

use Illuminate\Http\Request;
use Domain\Papers\Models\Diary;
use Domain\Papers\Models\Letter;
use Domain\Newspapers\Models\Topic;
use Domain\Newspapers\Models\Edition;
use Domain\Newspapers\Models\Newspaper;
use Domain\Censuses\Models\VeteranCensus;
use App\Api\Papers\Queries\DiaryIndexQuery;
use Domain\Claims\Models\ChambersburgClaim;
use App\Api\Papers\Queries\LetterIndexQuery;
use Domain\Censuses\Models\PopulationCensus;
use Domain\ChurchRecords\Models\ChurchRecord;
use Domain\Censuses\Models\SlaveowningCensus;
use Domain\CohabitationRecords\Models\Family;
use Domain\Censuses\Models\AgriculturalCensus;
use Domain\TaxRecords\Models\AugustaTaxRecord;
use Domain\Censuses\Models\ManufacturingCensus;
use Domain\TaxRecords\Models\FranklinTaxRecord;
use Domain\MemoryArticles\Models\MemoryArticle;
use App\Api\Newspapers\Queries\TopicIndexQuery;
use Domain\SoldierDossiers\Models\SoldierDossier;
use App\Api\Newspapers\Queries\EditionIndexQuery;
use App\Api\Newspapers\Queries\NewspaperIndexQuery;
use App\Api\Censuses\Queries\VeteranCensusIndexQuery;
use Domain\FreeBlackRegistry\Models\FreeBlackRegistry;
use Domain\Claims\Models\SouthernClaimsCommissionClaim;
use App\Api\Claims\Queries\ChambersburgClaimIndexQuery;
use App\Api\Censuses\Queries\PopulationCensusIndexQuery;
use Domain\CivilWarImages\Models\Image as CivilWarImage;
use App\Api\ChurchRecords\Queries\ChurchRecordIndexQuery;
use Domain\RegimentalMovements\Models\RegimentalMovement;
use App\Api\Censuses\Queries\SlaveowningCensusIndexQuery;
use App\Api\CohabitationRecords\Queries\FamilyIndexQuery;
use App\Api\Censuses\Queries\AgriculturalCensusIndexQuery;
use App\Api\TaxRecords\Queries\AugustaTaxRecordIndexQuery;
use App\Api\Censuses\Queries\ManufacturingCensusIndexQuery;
use App\Api\TaxRecords\Queries\FranklinTaxRecordIndexQuery;
use App\Api\MemoryArticles\Queries\MemoryArticleIndexQuery;
use Domain\FireInsurancePolicies\Models\FireInsurancePolicy;
use App\Api\SoldierDossiers\Queries\SoldierDossierIndexQuery;
use App\Api\Claims\Queries\SouthernClaimsCommissionIndexQuery;
use App\Api\FreeBlackRegistry\Queries\FreeBlackRegistryIndexQuery;
use App\Api\RegimentalMovements\Queries\RegimentalMovementIndexQuery;
use Domain\BattlefieldCorrespondence\Models\BattlefieldCorrespondence;
use App\Api\FireInsurancePolicies\Queries\FireInsurancePolicyIndexQuery;
use App\Api\CivilWarImages\Queries\ImageIndexQuery as CivilWarImageIndexQuery;
use App\Api\BattlefieldCorrespondence\Queries\BattlefieldCorrespondenceIndexQuery;

class AutocompleteController
{
    private $modelMap = [
        'agricultural_census' => [
            'modelClass' => AgriculturalCensus::class,
            'queryClass' => AgriculturalCensusIndexQuery::class,
        ],
        'augusta_tax_record' => [
            'modelClass' => AugustaTaxRecord::class,
            'queryClass' => AugustaTaxRecordIndexQuery::class,
        ],
        'battlefield_correspondence' => [
            'modelClass' => BattlefieldCorrespondence::class,
            'queryClass' => BattlefieldCorrespondenceIndexQuery::class,
        ],
        'chambersburg_claim' => [
            'modelClass' => ChambersburgClaim::class,
            'queryClass' => ChambersburgClaimIndexQuery::class,
        ],
        'church_record' => [
            'modelClass' => ChurchRecord::class,
            'queryClass' => ChurchRecordIndexQuery::class,
        ],
        'civil_war_image' => [
            'modelClass' => CivilWarImage::class,
            'queryClass' => CivilWarImageIndexQuery::class,
        ],
        'cohabitation_family' => [
            'modelClass' => Family::class,
            'queryClass' => FamilyIndexQuery::class,
        ],
        'diary' => [
            'modelClass' => Diary::class,
            'queryClass' => DiaryIndexQuery::class,
        ],
        'fire_insurance_policy' => [
            'modelClass' => FireInsurancePolicy::class,
            'queryClass' => FireInsurancePolicyIndexQuery::class,
        ],
        'franklin_tax_record' => [
            'modelClass' => FranklinTaxRecord::class,
            'queryClass' => FranklinTaxRecordIndexQuery::class,
        ],
        'free_black_registry' => [
            'modelClass' => FreeBlackRegistry::class,
            'queryClass' => FreeBlackRegistryIndexQuery::class,
        ],
        'letter' => [
            'modelClass' => Letter::class,
            'queryClass' => LetterIndexQuery::class,
        ],
        'manufacturing_census' => [
            'modelClass' => ManufacturingCensus::class,
            'queryClass' => ManufacturingCensusIndexQuery::class,
        ],
        'memory_article' => [
            'modelClass' => MemoryArticle::class,
            'queryClass' => MemoryArticleIndexQuery::class,
        ],
        'newspaper' => [
            'modelClass' => Newspaper::class,
            'queryClass' => NewspaperIndexQuery::class,
        ],
        'newspaper_edition' => [
            'modelClass' => Edition::class,
            'queryClass' => EditionIndexQuery::class,
        ],
        'newspaper_topic' => [
            'modelClass' => Topic::class,
            'queryClass' => TopicIndexQuery::class,
        ],
        'population_census' => [
            'modelClass' => PopulationCensus::class,
            'queryClass' => PopulationCensusIndexQuery::class,
        ],
        'slaveowning_census' => [
            'modelClass' => SlaveowningCensus::class,
            'queryClass' => SlaveowningCensusIndexQuery::class,
        ],
        'soldier_dossier' => [
            'modelClass' => SoldierDossier::class,
            'queryClass' => SoldierDossierIndexQuery::class,
        ],
        'southern_claims_commission' => [
            'modelClass' => SouthernClaimsCommissionClaim::class,
            'queryClass' => SouthernClaimsCommissionIndexQuery::class,
        ],
        'regimental_movement' => [
            'modelClass' => RegimentalMovement::class,
            'queryClass' => RegimentalMovementIndexQuery::class,
        ],
        'veteran_census' => [
            'modelClass' => VeteranCensus::class,
            'queryClass' => VeteranCensusIndexQuery::class,
        ],
    ];

    public function index(Request $request)
    {
        $modelName = $request->input('model');
        $modelClass = $this->modelMap[$modelName]['modelClass'] ?? null;
        $queryClass = $this->modelMap[$modelName]['queryClass'] ?? null;
        $limit = $request->input('limit') ?? 8;
        $field = $request->input('field');
        $value = $request->input('q');

        if ($modelClass && $queryClass && $field) {
            $query = new $queryClass($request);
            $results = $query->where($field, 'LIKE', "%{$value}%")
                ->whereNotNull($field)
                ->orderBy($field)
                ->groupBy($field)
                ->take($limit)
                ->get();

            return $results->map(function ($result) use ($field) {
                return [
                    'value' => $result->{$field},
                ];
            });
        } else {
            abort(400, 'Invalid model requested');
        }
    }
}

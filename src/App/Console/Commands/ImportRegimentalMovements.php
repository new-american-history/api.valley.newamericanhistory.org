<?php

namespace App\Console\Commands;

use App\Console\Commands\BaseImportCommand;
use Domain\RegimentalMovements\Models\Regiment;
use Domain\RegimentalMovements\Models\RegimentalMovement;

class ImportRegimentalMovements extends BaseImportCommand
{
    protected $signature = 'import:regimental-movements';

    protected $description = 'Import regimental movements';

    protected $file = 'data/mapdata.xml';

    protected $columnMap = [
        'battle' => 'battle_name',
        'brigade' => 'brigade',
        'commander' => 'commander',
        'corps' => 'corps',
        'division' => 'division',
        'gtown_weather' => 'georgetown_weather',
        'killed' => 'killed',
        'local_weather' => 'local_weather',
        'missing' => 'missing',
        'or_url' => '',
        'strength' => 'regiment_strength',
        'summary' => 'summary',
        'wounded' => 'wounded',
    ];

    protected $regimentColumnMap = [
        'dossiers_regiment' => 'name_in_dossiers',
        'regiment' => 'name',
    ];

    protected $stateMap = [
        'D.C.' => 'dc',
        'Md.' => 'maryland',
        'N.C.' => 'northCarolina',
        'PA' => 'pennsylvania',
        'Pa.' => 'pennsylvania',
        'VA' => 'virginia',
        'Va.' => 'virginia',
        'W.Va. and Va.' => 'westVirginiaAndVirginia',
        'W.Va.' => 'westVirginia',
    ];

    public function handle()
    {
        $data = self::getFileData($this->file);
        $document = self::getDomDocumentWithXml($data);
        $items = $document->getElementsByTagName('row');

        foreach ($items as $item) {
            if (!empty($item)) {
                $modelData = [];
                $regimentModelData = [];

                $columns = $item->getElementsByTagName('column');

                foreach ($columns as $column) {
                    $columnName = $column->getAttribute('name');
                    $value = self::getElementValue($column, ['n.a.']);

                    $modelAttribute = $this->columnMap[$column->getAttribute('name')] ?? null;
                    $regimentModelAttribute = $this->regimentColumnMap[$column->getAttribute('name')] ?? null;

                    switch ($columnName) {
                        case 'battle_date':
                            $modelData['battle_start_date'] = self::getFormattedDate($value);
                            break;
                        case 'battle_date2':
                            $modelData['battle_end_date'] = self::getFormattedDate($value);
                            break;
                        case 'regiment_state':
                            $regimentModelData['state'] = $this->stateMap[$value] ?? null;
                            if ($value === 'VA') {
                                $regimentModelData['county'] = 'augusta';
                            } elseif ($value === 'PA') {
                                $regimentModelData['county'] = 'franklin';
                            }
                            break;
                        case 'state':
                            $modelData['battle_state'] = $this->stateMap[$value] ?? null;
                            break;
                        default:
                            if (!empty($modelAttribute)) {
                                $modelData[$modelAttribute] = $value;
                            } elseif (!empty($regimentModelAttribute)) {
                                $regimentModelData[$regimentModelAttribute] = $value;
                            }
                            break;
                    }
                }

                if (!empty(array_filter($regimentModelData))) {
                    $regiment = Regiment::firstOrCreate($regimentModelData);
                    $modelData['regiment_id'] = $regiment->id;
                }

                if (!empty($modelData['battle_name'])) {
                    RegimentalMovement::create($modelData);
                }
            }
        }

        $this->info('Imported regimental movement data (' . $this->file . ')');
    }
}

<?php

namespace App\Console\Commands;

use App\Console\Commands\BaseImportCommand;
use Domain\TaxRecords\Models\AugustaTaxRecord;

class ImportAugustaTaxRecords extends BaseImportCommand
{
    protected $signature = 'import:augusta-tax-records';

    protected $description = 'Import tax records for Augusta';

    protected $file = 'data/tax_staunton_60.xml';

    protected $columnMap = [
        'last' => 'last_name',
        'first' => 'first_name',
        'other' => 'other_name',

        // 'pop_census' => '',
        // 'agr_census' => '',
        // 'slave_census' => '',

        'acres' => 'acres',
        'rods' => 'rods',
        'poles' => 'poles',
        'residence' => 'residence',
        'estate' => 'estate',
        
        'lot_num' => 'lot_number',
        'building_val' => 'building_value',
        'lot_building_val' => 'lot_building_value',
        
        'tax_amt' => 'tax_amount',
        'city_tax_amt' => 'city_tax_amount',
        
        'census_notes' => 'census_notes',
        'vcdh_notes' => 'notes',

        // 'id_num' => '',
    ];

    public function handle()
    {
        $data = self::getFileData($this->file);
        $document = self::getDomDocumentWithXml($data);
        $items = $document->getElementsByTagName('row');

        $county = 'augusta';
        $year = 1860;

        foreach ($items as $item) {
            if (!empty($item)) {
                $modelData = [];
                $modelData['county'] = $county;
                $modelData['year'] = $year;

                $columns = $item->getElementsByTagName('column');

                foreach ($columns as $column) {
                    $modelAttribute = $this->columnMap[$column->getAttribute('name')] ?? null;

                    if (!empty($modelAttribute)) {
                        $value = trim($column->nodeValue);
                        $modelData[$modelAttribute] = !empty($value) || $value === 0 ? $value : null;
                    }
                }

                AugustaTaxRecord::create($modelData);
            }
        }

        $this->info('Imported Augusta tax records (' . $this->file . ')');
    }
}

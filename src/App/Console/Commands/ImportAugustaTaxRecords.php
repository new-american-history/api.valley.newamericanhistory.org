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
        'acres' => 'acres',
        'building_val' => 'building_value',
        'census_notes' => 'census_notes',
        'city_tax_amt' => 'city_tax_amount',
        'estate' => 'estate',
        'first' => 'first_name',
        'last' => 'last_name',
        'lot_building_val' => 'lot_building_value',
        'lot_num' => 'lot_number',
        'other' => 'other_name',
        'poles' => 'poles',
        'residence' => 'residence',
        'rods' => 'rods',
        'tax_amt' => 'tax_amount',
        'vcdh_notes' => 'notes',
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
                    $columnName = $column->getAttribute('name');
                    $value = self::getElementValue($column, ['0']);

                    $modelAttribute = $this->columnMap[$columnName] ?? null;

                    if (!empty($modelAttribute)) {
                        $modelData[$modelAttribute] = $value;
                    }
                }

                AugustaTaxRecord::create($modelData);
            }
        }

        $this->info('Imported Augusta tax records (' . $this->file . ')');
    }
}

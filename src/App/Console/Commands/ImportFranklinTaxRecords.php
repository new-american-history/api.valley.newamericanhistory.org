<?php

namespace App\Console\Commands;

use App\Console\Commands\BaseImportCommand;
use Domain\TaxRecords\Models\FranklinTaxRecord;

class ImportFranklinTaxRecords extends BaseImportCommand
{
    protected $signature = 'import:franklin-tax-records';

    protected $description = 'Import tax records for Franklin';

    protected $file = 'data/tax_chburg_60.xml';

    protected $columnMap = [
        'amt_carriages' => 'carriages_tax_amount',
        'amt_cattle' => 'cattle_count',
        'amt_furniture' => 'furniture_tax_amount',
        'amt_horses' => 'horses_tax_amount',
        'amt_money' => 'money_tax_amount',
        'amt_per_acre_seat' => 'value_per_seated_acre',
        'amt_seat_town' => 'seated_lot_value',
        'amt_unseat_town' => 'unseated_lot_value',
        'amt_watches' => 'watches_tax_amount',
        'census_notes' => 'census_notes',
        'county_tax' => 'county_tax_amount',
        'first' => 'first_name',
        'last' => 'last_name',
        'mil_fine' => 'military_fine',
        'num_carriages' => 'carriages_count',
        'num_cattle' => 'cattle_tax_amount',
        'num_horses' => 'horses_count',
        'num_seat' => 'seated_acres',
        'num_seat_town' => 'number_seated_lots',
        'num_unseat' => 'unseated_acres',
        'num_unseat_town' => 'number_unseated_lots',
        'num_watches' => 'watches_count',
        'occ_val' => 'occupation_value',
        'occupation' => 'occupation',
        'other' => 'other_name',
        'state_personal_tax' => 'state_personal_tax_amount',
        'state_tax' => 'state_tax_amount',
        'tot_amt_seat' => 'seated_land_value',
        'tot_amt_unseat' => 'unseated_land_value',
        'ward' => 'ward',
    ];

    public function handle()
    {
        $data = self::getFileData($this->file);
        $document = self::getDomDocumentWithXml($data);
        $items = $document->getElementsByTagName('row');

        $county = 'franklin';
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

                FranklinTaxRecord::create($modelData);
            }
        }

        $this->info('Imported Franklin tax records (' . $this->file . ')');
    }
}

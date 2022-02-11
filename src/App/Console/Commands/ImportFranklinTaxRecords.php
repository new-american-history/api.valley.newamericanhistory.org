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
        'last' => 'last_name',
        'first' => 'first_name',
        'other' => 'other_name',
        'census_notes' => 'census_notes',

        // 'pop_census' => '',
        // 'agr_census' => '',

        'ward' => 'ward',
        'occupation' => 'occupation',
        'occ_val' => 'occupation_value',

        'num_seat' => 'seated_acres',
        'amt_per_acre_seat' => 'value_per_seated_acre',
        'tot_amt_seat' => 'seated_land_value',

        'num_unseat' => 'unseated_acres',
        'tot_amt_unseat' => 'unseated_land_value',

        'num_seat_town' => 'number_seated_lots',
        'amt_seat_town' => 'seated_lot_value',
        'num_unseat_town' => 'number_unseated_lots',
        'amt_unseat_town' => 'unseated_lot_value',

        'num_horses' => 'horses_count',
        'amt_horses' => 'horses_tax_amount',
        'num_cattle' => 'cattle_tax_amount',
        'amt_cattle' => 'cattle_count',
        'amt_money' => 'money_tax_amount',
        'amt_furniture' => 'furniture_tax_amount',
        'num_watches' => 'watches_count',
        'amt_watches' => 'watches_tax_amount',
        'num_carriages' => 'carriages_count',
        'amt_carriages' => 'carriages_tax_amount',

        'county_tax' => 'county_tax_amount',
        'state_tax' => 'state_tax_amount',
        'state_personal_tax' => 'state_personal_tax_amount',
        'mil_fine' => 'military_fine',

        // 'id_num' => '',
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

<?php

namespace App\Console\Commands;

use App\Console\Commands\BaseImportCommand;
use Domain\Censuses\Models\ManufacturingCensus;
use Domain\Censuses\Models\ManufacturingCensusProduct;
use Domain\Censuses\Models\ManufacturingCensusMaterial;

class ImportManufacturingCensus extends BaseImportCommand
{
    protected $signature = 'import:manufacturing-census';

    protected $description = 'Import data for the manufacturing census';

    protected $censusFiles = [
        'data/man_aug_60.xml',
        'data/man_aug_70.xml',
        'data/man_fr_60.xml',
        'data/man_fr_70.xml',
    ];

    protected $materialFiles = [
        'data/man_aug_mat_60.xml',
        'data/man_aug_mat_70.xml',
        'data/man_fr_mat_60.xml',
        'data/man_fr_mat_70.xml',
    ];

    protected $productFiles = [
        'data/man_aug_prod_60.xml',
        'data/man_aug_prod_70.xml',
        'data/man_fr_prod_60.xml',
        'data/man_fr_prod_70.xml',
    ];

    protected $censusColumnMap = [
        'business' => 'business',
        'business_class' => 'business_class',
        'cap_inv' => 'capital_invested',
        'children_hands' => 'child_hands',
        'female_hands' => 'female_hands',
        'female_wage' => 'female_wages',
        'horsepower' => 'horsepower',
        'id_num' => 'data_id',
        'location' => 'location',
        'machine_names' => 'machines',
        'male_hands' => 'male_hands',
        'male_wage' => 'male_wages',
        'months_active' => 'months_active',
        'name' => 'name',
        'notes' => 'notes',
        'num_on_page' => 'number_on_page',
        'number_machines' => 'number_of_machines',
        'page_num' => 'page_number',
        'power' => 'power',
        'total_wages' => 'total_wages',
    ];

    protected $materialColumnMap = [
        'id_num' => 'census_data_id',
        'kinds_mat' => 'type',
        'qty_mat' => 'quantity',
        'val_mat' => 'value',
    ];

    protected $productColumnMap = [
        'id_num' => 'census_data_id',
        'kinds_annual' => 'type',
        'qty_annual' => 'quantity',
        'val_annual' => 'value',
    ];

    public function handle()
    {
        self::handleCensuses();
        self::handleMaterials();
        self::handleProducts();
    }

    public function handleCensuses()
    {
        foreach ($this->censusFiles as $file) {
            $data = self::getFileData($file);
            $document = self::getDomDocumentWithXml($data);
            $items = $document->getElementsByTagName('row');

            $county = str_contains($file, '_aug_') ? 'augusta' : 'franklin';
            $year = str_contains($file, '_60') ? 1860 : 1870;

            foreach ($items as $item) {
                if (!empty($item)) {
                    $modelData = [];
                    $modelData['county'] = $county;
                    $modelData['year'] = $year;

                    $columns = $item->getElementsByTagName('column');

                    $firstName = '';
                    $middleName = '';
                    $lastName = '';

                    foreach ($columns as $column) {
                        $columnName = $column->getAttribute('name');
                        $value = static::getElementValue($column, ['-']);

                        if (in_array($columnName, ['first_name', 'middle_name', 'last_name'])) {
                            switch ($columnName) {
                                case 'first_name':
                                    $firstName = $value;
                                    break;
                                case 'middle_name':
                                    $middleName = $value;
                                    break;
                                case 'last_name':
                                    $lastName = $value;
                                    break;
                            }
                        } else {
                            $modelAttribute = $this->censusColumnMap[$columnName] ?? null;

                            if (!empty($modelAttribute)) {
                                $modelData[$modelAttribute] = $value;
                            }
                        }
                    }

                    $modelData['name'] = $modelData['name'] ?? implode(' ', array_filter([$firstName, $middleName, $lastName]));
                    ManufacturingCensus::create($modelData);
                }
            }

            $this->info('Imported manufacturing census data (' . $file . ')');
        }
    }

    public function handleMaterials()
    {
        self::handleRelated($this->materialFiles, $this->materialColumnMap, ManufacturingCensusMaterial::class, 'material');
    }

    public function handleProducts()
    {
        self::handleRelated($this->productFiles, $this->productColumnMap, ManufacturingCensusProduct::class, 'product');
    }

    public function handleRelated($files, $columnMap, $modelClass, $dataLabel = '')
    {
        foreach ($files as $file) {
            $data = self::getFileData($file);
            $document = self::getDomDocumentWithXml($data);
            $items = $document->getElementsByTagName('row');

            $county = str_contains($file, '_aug_') ? 'augusta' : 'franklin';
            $year = str_contains($file, '_60') ? 1860 : 1870;

            foreach ($items as $item) {
                if (!empty($item)) {
                    $modelData = [];
                    $columns = $item->getElementsByTagName('column');

                    foreach ($columns as $column) {
                        $columnName = $column->getAttribute('name');
                        $modelAttribute = $columnMap[$columnName] ?? null;
                        $value = static::getElementValue($column, [0]);

                        if (!empty($modelAttribute)) {
                            $modelData[$modelAttribute] = $value;
                        }

                        if ($columnName === 'id_num') {
                            $census = ManufacturingCensus::where('county', $county)
                                ->where('year', $year)
                                ->where('data_id', $value)
                                ->first();
                            $modelData['manufacturing_census_id'] = $census->id ?? null;
                        }
                    }

                    $modelClass::create($modelData);
                }
            }

            $this->info('Imported manufacturing census ' . $dataLabel . ' data (' . $file . ')');
        }
    }
}

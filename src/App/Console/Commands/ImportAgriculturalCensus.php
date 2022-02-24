<?php

namespace App\Console\Commands;

use DateTime;
use App\Console\Commands\BaseImportCommand;
use Domain\Censuses\Models\AgriculturalCensus;

class ImportAgriculturalCensus extends BaseImportCommand
{
    protected $signature = 'import:agricultural-census';

    protected $description = 'Import data for the agricultural census';

    protected $files = [
        'data/agr_aug_60.xml',
        'data/agr_aug_70.xml',
        'data/agr_fr_60.xml',
        'data/agr_fr_70.xml',
    ];

    protected $columnMap = [
        'barley' => 'barley_bushels',
        'bees_wax' => 'beeswax_pounds',
        'buckwheat' => 'buckwheat_bushels',
        'butter' => 'butter_pounds',
        'cane_sugar' => 'cane_sugar_hogsheads',
        'cheese' => 'cheese_pounds',
        'clover_seed' => 'clover_seed_bushels',
        'corn' => 'corn_bushels',
        'cotton' => 'cotton_bales',
        'cows' => 'cows',
        'farm_impl' => 'farm_implements_value',
        'farm_val' => 'farm_value',
        'first' => 'first_name',
        'flax' => 'flax_pounds',
        'flax_seed' => 'flax_seed_bushels',
        'forest_prod' => 'forest_products_value',
        'grass_seed' => 'grass_seed_bushels',
        'hay' => 'hay_tons',
        'hemp' => 'hemp_tons',
        'home_manu' => 'home_manufactures_value',
        'honey' => 'honey_pounds',
        'hops' => 'hops_pounds',
        'horses' => 'horses',
        'ir_potatos' => 'irish_potatoes_bushels',
        'last' => 'last_name',
        'location' => 'location',
        'maple_sugar' => 'maple_sugar_pounds',
        'market_gardens' => 'market_garden_produce_value',
        'middle' => 'middle_name',
        'milk' => 'milk_gallons',
        'molasses' => 'molasses_gallons',
        'mules' => 'mules_and_asses',
        'num_on_page' => 'number_on_page',
        'oats' => 'oats_bushels',
        'orchard' => 'orchard_products_value',
        'other_cattle' => 'other_cattle',
        'other_land' => 'other_unimproved_land_acres',
        'oxen' => 'oxen',
        'page_num' => 'page_number',
        'peas_beans' => 'peas_and_beans_bushels',
        'rice' => 'rice_pounds',
        'rye' => 'rye_bushels',
        'sheep' => 'sheep',
        'silk_cocoons' => 'silk_cocoons_pounds',
        'slaut_animals' => 'slaughtered_animals_value',
        'spring_wheat' => 'spring_wheat_bushels',
        'sw_potatos' => 'sweet_potatoes_bushels',
        'swine' => 'swine',
        'tobacco' => 'tobacco_pounds',
        'tot_animals' => 'total_animals',
        'tot_grain' => 'total_grain_bushels',
        'tot_impr' => 'improved_land_acres',
        'tot_land' => 'total_land_acres',
        'tot_livestock' => 'livestock_value',
        'tot_unimpr' => 'unimproved_land_acres',
        'tot_value' => 'total_value',
        'wages' => 'wages_paid',
        'wheat' => 'wheat_bushels',
        'wine' => 'wine_gallons',
        'winter_wheat' => 'winter_wheat_bushels',
        'woodland' => 'woodland_acres',
        'wool' => 'wool_pounds',
    ];

    public function handle()
    {
        foreach ($this->files as $file) {
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

                    foreach ($columns as $column) {
                        $modelAttribute = $this->columnMap[$column->getAttribute('name')] ?? null;

                        if (!empty($modelAttribute)) {
                            $value = self::getElementValue($column);
                            $modelData[$modelAttribute] = !empty($value) || $value === 0 ? $value : null;
                        }
                    }

                    AgriculturalCensus::create($modelData);
                }
            }

            $this->info('Imported agricultural census data (' . $file . ')');
        }
    }
}

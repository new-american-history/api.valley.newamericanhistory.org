<?php

namespace App\Console\Commands;

use DateTime;
use Domain\Censuses\Models\VeteranCensus;
use App\Console\Commands\BaseImportCommand;

class ImportVeteranCensus extends BaseImportCommand
{
    protected $signature = 'import:veteran-census';

    protected $description = 'Import data for the veteran census';

    protected $files = [
        'data/vet_census_90.xml',
        'data/vet_census_aug_90.xml',
    ];

    protected $defaultColumnMap = [
        'company' => 'company',
        'disability' => 'disability',
        'enum_dist_num' => 'enumerator_district',
        'enumerator' => 'enumerator',
        'family_num' => 'family_number',
        'first' => 'first_name',
        'house_num' => 'house_number',
        'last' => 'last_name',
        'length_of_service' => 'length_of_service',
        'location' => 'location',
        'num_on_page' => 'number_on_page',
        'other_info' => 'other_info',
        'post_office' => 'post_office',
        'rank' => 'rank',
        'regiment' => 'regiment',
        'remarks' => 'remarks',
        'service_length' => 'length_of_service',
        'sup_dist_num' => 'superior_district_number',
        'vos_page' => 'page_number',
        'widow_name' => 'widow_name',
    ];

    public function handle()
    {
        foreach ($this->files as $file) {
            $data = self::getFileData($file);
            $document = self::getDomDocumentWithXml($data);
            $items = $document->getElementsByTagName('row');

            foreach ($items as $item) {
                if (!empty($item)) {
                    $modelData = [];
                    $modelData['county'] = str_contains($file, 'vet_census_aug_90') ? 'augusta' : 'franklin';
                    $modelData['year'] = 1890;

                    $columns = $item->getElementsByTagName('column');

                    foreach ($columns as $column) {
                        $columnName = $column->getAttribute('name');
                        switch ($columnName) {
                            case 'enl_date':
                                $modelData['enlistment_date'] = static::getFormattedDate($column->nodeValue);
                                break;
                            case 'discharge_date':
                                $modelData['discharge_date'] = static::getFormattedDate($column->nodeValue);
                                break;
                            default:
                                $modelAttribute = $this->defaultColumnMap[$columnName] ?? null;
                                if (!empty($modelAttribute)) {
                                    $modelData[$modelAttribute] = trim($column->nodeValue) ?: null;
                                }
                                break;
                        }
                    }

                    VeteranCensus::create($modelData);
                }
            }

            $this->info('Imported veteran census data (' . $file . ')');
        }
    }

    public function getFormattedDate($value) {
        if (!empty($value) && $value !== 0) {
            $value = str_replace('00/', '01/', $value);
            $dateTime = DateTime::createFromFormat('m-d-y', $value) ?: DateTime::createFromFormat('m/d/y', $value);
            return !empty($dateTime) ? '18' . $dateTime->format('y-m-d') : null;
        }
    }
}

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
                        switch ($column->getAttribute('name')) {
                            case 'first':
                                $modelData['first_name'] = trim($column->nodeValue) ?: null;
                                break;
                            case 'last':
                                $modelData['last_name'] = trim($column->nodeValue) ?: null;
                                break;
                            case 'location':
                                $modelData['location'] = trim($column->nodeValue) ?: null;
                                break;
                            case 'post_office':
                                $modelData['post_office'] = trim($column->nodeValue) ?: null;
                                break;
                            case 'family_num':
                                $modelData['family_number'] = trim($column->nodeValue) ?: null;
                                break;
                            case 'house_num':
                                $modelData['house_number'] = $column->nodeValue ?: null;
                                break;
                            case 'widow_name':
                                $modelData['widow_name'] = trim($column->nodeValue) ?: null;
                                break;
                            case 'enl_date':
                                $modelData['enlistment_date'] = static::getFormattedDate($column->nodeValue);
                                break;
                            case 'discharge_date':
                                $modelData['discharge_date'] = static::getFormattedDate($column->nodeValue);
                                break;
                            case 'length_of_service':
                                $modelData['length_of_service'] = trim($column->nodeValue) ?: null;
                                break;
                            case 'service_length':
                                $modelData['length_of_service'] = trim($column->nodeValue) ?: null;
                                break;
                            case 'company':
                                $modelData['company'] = trim($column->nodeValue) ?: null;
                                break;
                            case 'disability':
                                $modelData['disability'] = trim($column->nodeValue) ?: null;
                                break;
                            case 'rank':
                                $modelData['rank'] = trim($column->nodeValue) ?: null;
                                break;
                            case 'regiment':
                                $modelData['regiment'] = trim($column->nodeValue) ?: null;
                                break;
                            case 'sup_dist_num':
                                $modelData['superior_district_number'] = $column->nodeValue ?: null;
                                break;
                            case 'remarks':
                                $modelData['remarks'] = trim($column->nodeValue) ?: null;
                                break;
                            case 'other_info':
                                $modelData['other_info'] = trim($column->nodeValue) ?: null;
                                break;
                            case 'enumerator':
                                $modelData['enumerator'] = trim($column->nodeValue) ?: null;
                                break;
                            case 'enum_dist_num':
                                $modelData['enumerator_district'] = trim($column->nodeValue) ?: null;
                                break;
                            case 'vos_page':
                                $modelData['page_number'] = $column->nodeValue ?: null;
                                break;
                            case 'num_on_page':
                                $modelData['number_on_page'] = $column->nodeValue ?: null;
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

<?php

namespace App\Console\Commands;

use App\Console\Commands\BaseImportCommand;
use Domain\Censuses\Models\PopulationCensus;

class ImportPopulationCensus extends BaseImportCommand
{
    protected $signature = 'import:population-census';

    protected $description = 'Import data for the population census';

    protected $files = [
        'data/pop_aug_60.xml',
        'data/pop_aug_70.xml',
        'data/pop_fr_60.xml',
        'data/pop_fr_70.xml',
        'data/pop_fr_70_v2.xml',
    ];

    protected $defaultColumnMap = [
        'age' => 'age',
        'birth_place' => 'birthplace',
        'color' => 'race',
        'deaf_dumb' => 'disability',
        'district' => 'district',
        'dwelling_num' => 'dwelling_number',
        'family_num' => 'family_number',
        'first' => 'first_name',
        'head_num' => 'head_number',
        'middle' => 'middle_name',
        'notes' => 'notes',
        'occupation' => 'occupation',
        'page_num' => 'page_number',
        'persest' => 'personal_estate_value',
        'post_office' => 'post_office',
        'realest' => 'real_estate_value',
        'sex' => 'sex',
        'subdistrict' => 'subdistrict',
        'suffix' => 'suffix',
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

                    $itemIsEmpty = false;
                    $columns = $item->getElementsByTagName('column');

                    foreach ($columns as $column) {
                        if (!$itemIsEmpty) {
                            $columnName = $column->getAttribute('name');
                            switch ($columnName) {
                                case 'last':
                                    $value = trim($column->nodeValue);
                                    if ($value === 'Unoccupied') {
                                        $itemIsEmpty = true;
                                        break;
                                    }
                                    $modelData['last_name'] = $value ?: null;
                                    break;
                                case 'occcode':
                                    $value = trim($column->nodeValue);
                                    $modelData['occupation_code'] = $value && $value !== 'NULL' ? $value : null;
                                    break;
                                case 'school':
                                    $modelData['attended_school'] = self::getBoolean($column->nodeValue);
                                    break;
                                case 'read':
                                    $modelData['cannot_read'] = self::getBoolean($column->nodeValue);
                                    break;
                                case 'write':
                                    $modelData['cannot_write'] = self::getBoolean($column->nodeValue);
                                    break;
                                case 'readwrite':
                                    if (trim($column->nodeValue) === 'yes') {
                                        $modelData['cannot_read'] = true;
                                        $modelData['cannot_write'] = true;
                                    }
                                    break;
                                case 'for_father':
                                    $modelData['father_foreign_born'] = self::getBoolean($column->nodeValue);
                                    break;
                                case 'for_mother':
                                    $modelData['mother_foreign_born'] = self::getBoolean($column->nodeValue);
                                    break;
                                case 'male_21':
                                    $modelData['male_citizen'] = self::getBoolean($column->nodeValue);
                                    break;
                                case 'male_novote':
                                    $modelData['male_citizen_novote'] = self::getBoolean($column->nodeValue);
                                    break;
                                case 'married':
                                    $modelData['married_within_the_year'] = self::getBoolean($column->nodeValue);
                                    break;
                                case 'marr_month':
                                    $modelData['marriage_month'] = self::getMonthAsInteger($column->nodeValue);
                                    break;
                                case 'birth_month':
                                    $modelData['birth_month'] = self::getMonthAsInteger($column->nodeValue);
                                    break;
                                case 'date_taken':
                                    $modelData['date_taken'] = self::getFormattedDate(trim($column->nodeValue)) ?: null;
                                    break;
                                default:
                                    $modelAttribute = $this->defaultColumnMap[$columnName] ?? null;
                                    if (!empty($modelAttribute)) {
                                        $modelData[$modelAttribute] = trim($column->nodeValue) ?: null;
                                    }
                                    break;
                            }
                        }
                    }

                    if (!$itemIsEmpty) {
                        PopulationCensus::create($modelData);
                    }
                }
            }

            $this->info('Imported population census data (' . $file . ')');
        }
    }

    public function getFormattedDate($value) {
        $value = str_replace('?', '1', $value);
        $dateTime = strtotime($value);
        return !empty($dateTime) ? date('Y-m-d', $dateTime) : null;
    }
}

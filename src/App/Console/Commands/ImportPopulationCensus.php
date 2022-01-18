<?php

namespace App\Console\Commands;

use Domain\Censuses\Models\PopulationCensus;
use App\Console\Commands\BaseImportCommand;

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
                            switch ($column->getAttribute('name')) {
                                case 'first':
                                    $modelData['first_name'] = trim($column->nodeValue) ?: null;
                                    break;
                                case 'middle':
                                    $modelData['middle_name'] = trim($column->nodeValue) ?: null;
                                    break;
                                case 'last':
                                    $value = trim($column->nodeValue);
                                    if ($value === 'Unoccupied') {
                                        $itemIsEmpty = true;
                                        break;
                                    }
                                    $modelData['last_name'] = $value ?: null;
                                    break;
                                case 'suffix':
                                    $modelData['suffix'] = trim($column->nodeValue) ?: null;
                                    break;
                                case 'age':
                                    $modelData['age'] = trim($column->nodeValue) ?: null;
                                    break;
                                case 'birth_place':
                                    $modelData['birthplace'] = trim($column->nodeValue) ?: null;
                                    break;
                                case 'color':
                                    $modelData['race'] = trim($column->nodeValue) ?: null;
                                    break;
                                case 'sex':
                                    $modelData['sex'] = trim($column->nodeValue) ?: null;
                                    break;
                                case 'occcode':
                                    $value = trim($column->nodeValue);
                                    $modelData['occupation_code'] = $value && $value !== 'NULL' ? $value : null;
                                    break;
                                case 'occupation':
                                    $modelData['occupation'] = trim($column->nodeValue) ?: null;
                                    break;
                                case 'dwelling_num':
                                    $modelData['dwelling_number'] = trim($column->nodeValue) ?: null;
                                    break;
                                case 'family_num':
                                    $modelData['family_number'] = trim($column->nodeValue) ?: null;
                                    break;
                                case 'head_num':
                                    $modelData['head_number'] = trim($column->nodeValue) ?: null;
                                    break;
                                case 'school':
                                    $modelData['attended_school'] = static::getBoolean($column->nodeValue);
                                    break;
                                case 'read':
                                    $modelData['cannot_read'] = static::getBoolean($column->nodeValue);
                                    break;
                                case 'write':
                                    $modelData['cannot_write'] = static::getBoolean($column->nodeValue);
                                    break;
                                case 'readwrite':
                                    if (trim($column->nodeValue) === 'yes') {
                                        $modelData['cannot_read'] = true;
                                        $modelData['cannot_write'] = true;
                                    }
                                    break;
                                case 'deaf_dumb':
                                    $modelData['disability'] = trim($column->nodeValue) ?: null;
                                    break;
                                case 'for_father':
                                    $modelData['father_foreign_born'] = static::getBoolean($column->nodeValue);
                                    break;
                                case 'for_mother':
                                    $modelData['mother_foreign_born'] = static::getBoolean($column->nodeValue);
                                    break;
                                case 'male_21':
                                    $modelData['male_citizen'] = static::getBoolean($column->nodeValue);
                                    break;
                                case 'male_novote':
                                    $modelData['male_citizen_novote'] = static::getBoolean($column->nodeValue);
                                    break;
                                case 'married':
                                    $modelData['married_within_the_year'] = static::getBoolean($column->nodeValue);
                                    break;
                                case 'marr_month':
                                    $modelData['marriage_month'] = static::getMonthAsInteger($column->nodeValue);
                                    break;
                                case 'birth_month':
                                    $modelData['birth_month'] = static::getMonthAsInteger($column->nodeValue);
                                    break;
                                case 'persest':
                                    $modelData['personal_estate_value'] = trim($column->nodeValue) ?: null;
                                    break;
                                case 'realest':
                                    $modelData['real_estate_value'] = trim($column->nodeValue) ?: null;
                                    break;
                                case 'date_taken':
                                    $modelData['date_taken'] = static::getFormattedDate(trim($column->nodeValue)) ?: null;
                                    break;
                                case 'district':
                                    $modelData['district'] = trim($column->nodeValue) ?: null;
                                    break;
                                case 'subdistrict':
                                    $modelData['subdistrict'] = trim($column->nodeValue) ?: null;
                                    break;
                                case 'post_office':
                                    $modelData['post_office'] = trim($column->nodeValue) ?: null;
                                    break;
                                case 'page_num':
                                    $modelData['page_number'] = trim($column->nodeValue) ?: null;
                                    break;
                                case 'notes':
                                    $modelData['notes'] = trim($column->nodeValue) ?: null;
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

    public function getMonthAsInteger($value) {
        $value = trim($value);
        $dateTime = strtotime($value);
        return !empty($dateTime) ? date('m', $dateTime) : null;
    }

    public function getBoolean($value) {
        $value = trim($value);
        if ($value === 'yes' || $value === '1') {
            return true;
        } elseif ($value === '1') {
            return false;
        }
        return null;
    }
}

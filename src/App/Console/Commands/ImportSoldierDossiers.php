<?php

namespace App\Console\Commands;

use Domain\Shared\Models\Image;
use App\Console\Commands\BaseImportCommand;
use Domain\SoldierDossiers\Models\SoldierDossier;

class ImportSoldierDossiers extends BaseImportCommand
{
    protected $signature = 'import:soldier-dossiers';

    protected $description = 'Import data for soldier dossiers';

    protected $files = [
        'data/dossiers_augusta.xml',
        'data/dossiers_franklin_full.xml',
        // 'data/va_rosters.xml',
    ];

    protected $columnMap = [
        'age_enl' => 'enlisted_age',
        'awol' => 'awol_summary',
        'birth_place' => 'birthplace',
        'burial_place' => 'burial_location',
        'capture' => 'captured_summary',
        'cause_of_death' => 'cause_of_death',
        'company' => 'company',
        'company_transfer' => 'transfer_company',
        'consript_sub' => 'conscript_or_substitute',
        'dead_disease' => 'died_of_disease_summary',
        'dead_wounds' => 'died_of_wounds_summary',
        'death_place' => 'death_location',
        'desertion' => 'deserted_summary',
        'discharge' => 'discharged_summary',
        'dwelling_1860' => '1860_census_dwelling_number',
        'epitaph' => 'epitaph',
        'family_1860' => '1860_census_family_number',
        'first' => 'first_name',
        'hospital_record' => 'hospital_record',
        'kia_info' => 'kia_summary',
        'kia_place' => 'kia_location',
        'last' => 'last_name',
        'mia' => 'mia_summary',
        'muster_record' => 'muster_record',
        'notes' => 'notes',
        'occ_enl' => 'enlisted_occupation',
        'page_1860' => '1860_census_page_number',
        'paroled' => 'paroled_summary',
        'personal_info' => 'personal_info',
        'phys_desc' => 'physical_description',
        'place_enl' => 'enlisted_location',
        'postwar_life' => 'postwar_life',
        'prewar' => 'prewar_life',
        'prisoner' => 'pow_summary',
        'promotions' => 'promotions',
        'rank_enl' => 'enlisted_rank',
        'regiment' => 'regiment',
        'transfers' => 'transfers',
        'wia' => 'wia_summary',
    ];

    protected $dateMap = [
        'awol_day' => 'awol_date',
        'awol_month' => 'awol_date',
        'awol_year' => 'awol_date',
        'birth_day' => 'birthday',
        'birth_month' => 'birthday',
        'birth_year' => 'birthday',
        'captured_day' => 'captured_date',
        'captured_month' => 'captured_date',
        'captured_year' => 'captured_date',
        'day_enl' => 'enlisted_date',
        'death_day' => 'death_date',
        'death_month' => 'death_date',
        'death_year' => 'death_date',
        'desertion_day' => 'deserted_date',
        'desertion_month' => 'deserted_date',
        'desertion_year' => 'deserted_date',
        'discharge_day' => 'discharged_date',
        'discharge_month' => 'discharged_date',
        'discharge_year' => 'discharged_date',
        'dod_day' => 'died_of_disease_date',
        'dod_month' => 'died_of_disease_date',
        'dod_year' => 'died_of_disease_date',
        'dow_day' => 'died_of_wounds_date',
        'dow_month' => 'died_of_wounds_date',
        'dow_year' => 'died_of_wounds_date',
        'kia_day' => 'kia_date',
        'kia_month' => 'kia_date',
        'kia_year' => 'kia_date',
        'mia_day' => 'mia_date',
        'mia_month' => 'mia_date',
        'mia_year' => 'mia_date',
        'month_enl' => 'enlisted_date',
        'paroled_day' => 'paroled_date',
        'paroled_month' => 'paroled_date',
        'paroled_year' => 'paroled_date',
        'pow_day' => 'pow_date',
        'pow_month' => 'pow_date',
        'pow_year' => 'pow_date',
        'wia_day' => 'wia_date',
        'wia_month' => 'wia_date',
        'wia_year' => 'wia_date',
        'year_enl' => 'enlisted_date',
    ];

    public function handle()
    {
        foreach ($this->files as $file) {
            $data = self::getFileData($file);
            $document = self::getDomDocumentWithXml($data);
            $items = $document->getElementsByTagName('row');

            $county = str_contains($file, '_augusta') || str_contains($file, 'va_') ? 'augusta' : 'franklin';

            foreach ($items as $item) {
                if (!empty($item)) {
                    $modelData = [];
                    $modelData['county'] = $county;

                    $columns = $item->getElementsByTagName('column');

                    foreach ($columns as $column) {
                        $columnName = $column->getAttribute('name');
                        $value = static::getElementValue($column, ['0']);

                        if (in_array($columnName, array_keys($this->dateMap))) {
                            $modelAttribute = $this->dateMap[$columnName] ?? null;

                            if (str_contains($columnName, 'year')) {
                                $value = $value ? str_pad($value, 4, '18', STR_PAD_LEFT) : null;
                                $part = 'year';
                            } elseif (str_contains($columnName, 'month')) {
                                $value = $value ? str_pad($value, 2, '0', STR_PAD_LEFT) : null;
                                $part = 'month';
                            } elseif (str_contains($columnName, 'day')) {
                                $value = $value ? str_pad($value, 2, '0', STR_PAD_LEFT) : null;
                                $part = 'day';
                            }

                            if (!empty($modelAttribute) && !empty($part)) {
                                $modelData[$modelAttribute][$part] = $value;
                            }
                        } elseif ($columnName === 'photos') {
                            if (!empty($value)) {
                                $value = str_ireplace('gif', 'jpg', $value);
                                $image = Image::firstOrCreate(['path' => $value]);
                                $modelData['image_id'] = $image->id;
                            }
                            break;
                        } else {
                            $modelAttribute = $this->columnMap[$columnName] ?? null;

                            if (!empty($modelAttribute)) {
                                $modelData[$modelAttribute] = $value;
                            }
                        }
                    }

                    $modelData = self::combineAndFilterDates($modelData);
                    SoldierDossier::create($modelData);
                }
            }

            $this->info('Imported soldier dossier data (' . $file . ')');
        }
    }

    public function combineAndFilterDates(&$modelData) {
        foreach ($modelData as $key => $value) {
            if (is_array($value)) {
                $year = $value['year'] ?? null;
                $month = $value['month'] ?? '01';
                $day = $value['day'] ?? '01';

                if (!empty($year) && (!empty($month) || !empty($day))) {
                    $dateTime = strtotime("{$year}-{$month}-{$day}");
                    $modelData[$key] = !empty($dateTime) ? date('Y-m-d', $dateTime) : null;
                } else {
                    $modelData[$key] = null;
                }
            }
        }

        return $modelData;
    }
}

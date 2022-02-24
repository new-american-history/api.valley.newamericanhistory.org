<?php

namespace App\Console\Commands;

use App\Console\Commands\BaseImportCommand;
use Domain\CohabitationRecords\Models\Child;
use Domain\CohabitationRecords\Models\Family;

class ImportCohabitationRecords extends BaseImportCommand
{
    protected $signature = 'import:cohabitation-records';

    protected $description = 'Import cohabitation records';

    protected $familiesFile = 'data/cohabitation_1866.xml';

    protected $childrenFile = 'data/cohabitation_1866_children.xml';

    protected $familyColumnMap = [
        'cohab_current_residence' => 'residence',
        'cohab_family_id' => 'family_id',
        'cohab_husband_age' => 'husband_age',
        'cohab_husband_birth_place' => 'husband_birthplace',
        'cohab_husband_first_name' => 'husband_first_name',
        'cohab_husband_last_name' => 'husband_last_name',
        'cohab_husband_occupation' => 'husband_occupation',
        'cohab_id' => 'alternate_family_id',
        'cohab_num_children' => 'number_of_children',
        'cohab_original_remarks' => 'original_remarks',
        'cohab_report_date' => 'report_date',
        'cohab_wife_age' => 'wife_age',
        'cohab_wife_birth_place' => 'wife_birthplace',
        'cohab_wife_first_name' => 'wife_first_name',
        'cohab_wife_last_name' => 'wife_last_name',
    ];

    protected $childColumnMap = [
        'child_family_id' => 'family_id',
        'child_father_first_name' => 'father_first_name',
        'child_father_last_name' => 'father_last_name',
        'child_name' => 'name',
        'child_age' => 'age',
    ];

    public function handle()
    {
        $this->importFamilies();
        $this->importChildren();
    }

    public function importFamilies() {
        $data = self::getFileData($this->familiesFile);
        $document = self::getDomDocumentWithXml($data);
        $items = $document->getElementsByTagName('row');

        $county = 'augusta';

        foreach ($items as $item) {
            if (!empty($item)) {
                $modelData = [];
                $modelData['county'] = $county;

                $columns = $item->getElementsByTagName('column');

                foreach ($columns as $column) {
                    $modelAttribute = $this->familyColumnMap[$column->getAttribute('name')] ?? null;

                    if (!empty($modelAttribute)) {
                        $modelData[$modelAttribute] = self::getElementValue($column, ['[No entry]', 0]);
                    }
                }

                // Accommodate the one record with a family_id of `0`.
                $modelData['family_id'] =
                    empty($modelData['family_id']) || $modelData['family_id'] === 0
                        ? $modelData['alternate_family_id']
                        : $modelData['family_id'];
                unset($modelData['alternate_family_id']);

                Family::create($modelData);
            }
        }

        $this->info('Imported cohabitation family records (' . $this->familiesFile . ')');
    }

    public function importChildren() {
        $data = self::getFileData($this->childrenFile);
        $document = self::getDomDocumentWithXml($data);
        $items = $document->getElementsByTagName('row');

        foreach ($items as $item) {
            if (!empty($item)) {
                $modelData = [];
                $columns = $item->getElementsByTagName('column');

                foreach ($columns as $column) {
                    $modelAttribute = $this->childColumnMap[$column->getAttribute('name')] ?? null;

                    if (!empty($modelAttribute)) {
                        $value = trim($column->nodeValue);
                        $modelData[$modelAttribute] = !empty($value) || $value === 0 ? $value : null;
                    }
                }

                if (!empty($modelData['name'])) {
                    Child::create($modelData);
                }
            }
        }

        $this->info('Imported cohabitation child records (' . $this->childrenFile . ')');
    }
}

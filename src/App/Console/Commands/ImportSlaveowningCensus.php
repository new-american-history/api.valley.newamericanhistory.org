<?php

namespace App\Console\Commands;

use App\Console\Commands\BaseImportCommand;
use Domain\Censuses\Models\SlaveowningCensus;

class ImportSlaveowningCensus extends BaseImportCommand
{
    protected $signature = 'import:slaveowning-census';

    protected $description = 'Import data for the slaveowner census';

    protected $file = 'data/slave_aug_60.xml';

    protected $defaultColumnMap = [
        'black_slaves' => 'black_slaves',
        'emp_location' => 'employer_location',
        'emp_name' => 'employer_name',
        'female_slaves' => 'female_slaves',
        'last' => 'last_name',
        'location' => 'location',
        'male_slaves' => 'male_slaves',
        'mulatto_slaves' => 'mulatto_slaves',
        'total_slaves' => 'total_slaves',
    ];

    public function handle()
    {
        $data = self::getFileData($this->file);
        $data = str_replace('&quot;', '', $data);
        $document = self::getDomDocumentWithXml($data);
        $items = $document->getElementsByTagName('row');

        foreach ($items as $item) {
            if (!empty($item)) {
                $modelData = [];
                $modelData['county'] = 'augusta';
                $modelData['year'] = 1860;

                $columns = $item->getElementsByTagName('column');

                foreach ($columns as $column) {
                    $columnName = $column->getAttribute('name');
                    $value = static::getElementValue($column);

                    if ($columnName === 'first') {
                        $firstName = $value;
                        $modelData['first_name'] = (!empty($firstName) && $firstName !== '#emp.') ? $firstName : null;
                    } else {
                        $modelAttribute = $this->defaultColumnMap[$columnName] ?? null;
                        if (!empty($modelAttribute)) {
                            $modelData[$modelAttribute] = $value ?: null;
                        }
                    }
                }

                SlaveowningCensus::create($modelData);
            }
        }

        $this->info('Imported slaveowner census data (' . $this->file . ')');
    }
}

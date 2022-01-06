<?php

namespace App\Console\Commands;

use App\Console\Commands\BaseImportCommand;
use Domain\Censuses\Models\SlaveowningCensus;

class ImportSlaveowningCensus extends BaseImportCommand
{
    protected $signature = 'import:slaveowning-census';

    protected $description = 'Import data for the slaveowner census';

    protected $file = 'data/slave_aug_60.xml';

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
                    switch ($column->getAttribute('name')) {
                        case 'first':
                            $firstName = trim($column->nodeValue);
                            $modelData['first_name'] = (!empty($firstName) && $firstName !== '#emp.') ? $firstName : null;
                            break;
                        case 'last':
                            $modelData['last_name'] = trim($column->nodeValue) ?? null;
                            break;
                        case 'location':
                            $modelData['location'] = trim($column->nodeValue) ?? null;
                            break;
                        case 'emp_name':
                            $modelData['employer_name'] = trim($column->nodeValue) ?? null;
                            break;
                        case 'emp_location':
                            $modelData['employer_location'] = trim($column->nodeValue) ?? null;
                            break;
                        case 'total_slaves':
                            $modelData['total_slaves'] = $column->nodeValue ?? null;
                            break;
                        case 'black_slaves':
                            $modelData['black_slaves'] = $column->nodeValue ?? null;
                            break;
                        case 'mulatto_slaves':
                            $modelData['mulatto_slaves'] = $column->nodeValue ?? null;
                            break;
                        case 'female_slaves':
                            $modelData['female_slaves'] = $column->nodeValue ?? null;
                            break;
                        case 'male_slaves':
                            $modelData['male_slaves'] = $column->nodeValue ?? null;
                            break;
                    }
                }

                SlaveowningCensus::create($modelData);
            }
        }

        $this->info('Imported slaveowner census data (' . $this->file . ')');
    }
}

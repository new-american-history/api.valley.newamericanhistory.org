<?php

namespace App\Console\Commands;

use DateTime;
use Illuminate\Support\Carbon;
use App\Console\Commands\BaseImportCommand;
use Domain\ChurchRecords\Models\ChurchRecord;

class ImportChurchRecords extends BaseImportCommand
{
    protected $signature = 'import:church-records';

    protected $description = 'Import church records';

    protected $files = [
        'data/church_aug.xml',
        'data/church_fr.xml',
    ];

    protected $columnMap = [
        'church' => 'church_name',
        'clergy' => 'clergy',
        'family' => 'family',
        'first' => 'first_name',
        'last' => 'last_name',
        'location' => 'location',
        'notes' => 'notes',
        'race' => 'race',
        'record_type' => 'record_type',
        'sex' => 'sex',
        'witness' => 'witness',
    ];

    protected $dateRecordKeys = [
        'baptism',
        'communion',
        'confirmation',
        'death',
        'marriage',
    ];

    public function handle()
    {
        foreach ($this->files as $file) {
            $data = self::getFileData($file);
            $document = self::getDomDocumentWithXml($data);
            $items = $document->getElementsByTagName('row');

            $county = str_contains($file, '_aug') ? 'augusta' : 'franklin';

            foreach ($items as $item) {
                if (!empty($item)) {
                    $modelData = [];
                    $modelData['county'] = $county;

                    $columns = $item->getElementsByTagName('column');

                    foreach ($columns as $column) {
                        $attributeName = $column->getAttribute('name');
                        $modelAttribute = $this->columnMap[$attributeName] ?? null;
                        $value = self::getElementValue($column, ['NA']);
                        $value = str_replace('"', '', $value);
                        
                        if ($attributeName === 'dob' && !empty($value)) {
                            // Manually parse out date of birth/age.
                            $cleanValue = self::cleanDateString($value);
                            if (!empty($cleanValue)) {
                                try {
                                    $modelData['dob'] = new Carbon($cleanValue);
                                } catch (\Carbon\Exceptions\InvalidFormatException $error) {
                                    echo "CARBON_INVALID_FORMAT: `dob` {$value} ({$cleanValue})\n";
                                    $modelData['age'] = $value;
                                }
                            } else {
                                $modelData['age'] = $value;
                            }
                        } elseif (in_array($attributeName, $this->dateRecordKeys) && !empty($value)) {
                            // Manually assign the various date columns into one.
                            $modelData['date_written'] = $value;

                            $cleanValue = self::cleanDateString($value);

                            if (!empty($cleanValue)) {
                                try {
                                    $modelData['date'] = new Carbon($cleanValue);
                                } catch (\Carbon\Exceptions\InvalidFormatException $error) {
                                    echo "CARBON_INVALID_FORMAT: `{$attributeName}` {$value} ({$cleanValue})\n";
                                }
                            }
                        } elseif (!empty($modelAttribute)) {
                            $modelData[$modelAttribute] = !empty($value) || $value === 0 ? $value : null;
                        }
                    }

                    if (!empty($modelData['church_name'])
                        && !empty($modelData['record_type'])
                    ) {
                        ChurchRecord::create($modelData);
                    }
                }
            }

            $this->info('Imported church records (' . $file . ')');
        }
    }

    public function cleanDateString($str)
    {
        $str = str_ireplace('(', '', $str);
        $str = str_ireplace(')', '', $str);
        $str = str_ireplace(',', ' ', $str);
        $str = str_ireplace('_', '', $str);

        $str = str_ireplace('NA', '', $str);
        $str = str_ireplace('about', '', $str);
        $str = str_ireplace('circa ', '', $str);
        $str = str_ireplace('no year given', '', $str);

        $str = str_ireplace('not admitted', '', $str);
        $str = str_ireplace('As of', '', $str);
        $str = str_ireplace('By', '', $str);
        $str = str_ireplace('Buried on', '', $str);
        $str = str_ireplace('Buried', '', $str);
        $str = str_ireplace('Died', '', $str);

        $str = str_ireplace('Sabbath ', '', $str);

        $str = str_ireplace('Jaury', 'January', $str);
        $str = str_ireplace('Febraury', 'February', $str);
        $str = str_ireplace('Augusta', 'August', $str);

        $str = str_ireplace('1 843', '1843', $str);

        $str = preg_match('/\s*\w+\s+\d{1,2}\s+\d{4}\s*/', $str)
            ? $str
            : null;

        return $str;
    }
}

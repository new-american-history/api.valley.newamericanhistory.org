<?php

namespace App\Console\Commands;

use App\Console\Commands\BaseImportCommand;
use Domain\FreeBlackRegistry\Models\FreeBlackRegistry;

class ImportFreeBlackRegistry extends BaseImportCommand
{
    protected $signature = 'import:free-black-registry';

    protected $description = 'Import data for the Free Black Registry';

    protected $files = [
        'VoS/govdoc/fblack.early.html',
        'VoS/govdoc/fblack.late.html',
        'VoS/govdoc/fblack2.html',
    ];

    public function handle()
    {
        foreach ($this->files as $file) {
            $data = self::getFileData($file);
            $items = preg_split('/<hr\s?.*\/?>/', $data);

            foreach ($items as $item) {
                $item = strstr($item, '<b>');
                if (!empty($item)) {
                    $item = str_replace("\n", ' ', $item);
                    $document = self::getDomDocumentWithHtml($item);

                    if (!empty($document)) {
                        $modelData = [];
                        $modelData['county'] = 'augusta';
                        $modelData['city'] = str_contains($file, 'fblack2') ? 'Staunton' : null;

                        $nameElement = static::getFirstElementByTagName($document, 'b');
                        if (!empty($nameElement)) {
                            $nameValue = static::getElementValue($nameElement);
                            $modelData['name'] = trim($nameValue, ', ');
                            $nameElement->parentNode->removeChild($nameElement);
                        }

                        $description = $document->saveHTML(static::getFirstElementByTagName($document, 'body'));
                        $description = preg_replace('/<\/?body>/', '', $description);
                        $description = trim(str_replace('<p></p>', '', $description));

                        $matches = [];

                        if (str_contains($file, 'fblack2')) {
                            preg_match('/No\.?\s*(\d+)/', $description, $matches);
                            $modelData['number'] = $matches[1] ?? null;
                            $modelData['description'] = $description;
                        } else {
                            preg_match('/No\.? (\d+)\s+([\s\S]*)/', $description, $matches);
                            $modelData['number'] = $matches[1] ?? null;
                            $modelData['description'] = $matches[2] ?? $description;
                        }

                        FreeBlackRegistry::create($modelData);
                    }
                }
            }

            $this->info('Imported Free Black Registry data (' . $file . ')');
        }
    }
}

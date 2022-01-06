<?php

namespace App\Console\Commands;

use DOMDocument;
use Illuminate\Console\Command;
use Domain\FreeBlackRegistry\Models\FreeBlackRegistry;

class ImportFreeBlackRegistry extends Command
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
            $path = storage_path() . '/import-data/' . $file;
            $data = file_get_contents($path);
            $items = preg_split('/<hr\s?.*\/?>/', $data);

            foreach ($items as $item) {
                $item = strstr($item, '<b>');
                if (!empty($item)) {
                    $item = str_replace("\n", ' ', $item);
                    $document = self::getDomWithHTML($item);

                    if (!empty($document)) {
                        $modelData = [];
                        $modelData['county'] = 'augusta';
                        $modelData['city'] = str_contains($file, 'fblack2') ? 'Staunton' : null;

                        $name = $document->getElementsByTagName('b')->item(0) ?? null;
                        if (!empty($name)) {
                            $nameValue = $name->nodeValue;
                            $modelData['name'] = trim($nameValue, ', ');
                            $name->parentNode->removeChild($name);
                        }

                        $description = $document->saveHTML($document->getElementsByTagName('body')->item(0));
                        $description = preg_replace('/<\/?body>/', '', $description);
                        $description = trim(preg_replace('/<p><\/p>/', '', $description));

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

                        $model = FreeBlackRegistry::create($modelData);
                    }
                }
            }

            $this->info('Imported Free Black Registry: ' . $file);
        }
    }

    public function getDomWithHTML($html)
    {
        libxml_use_internal_errors(true);

        $document = new DOMDocument();
        $document->loadHTML($html, LIBXML_HTML_NODEFDTD);

        foreach (libxml_get_errors() as $error) {
            if (!in_array(trim($error->message), ['Unexpected end tag : p'])) {
                echo $error->message;
            }
        }
        libxml_use_internal_errors(false);

        return $document ?? null;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\File;
use App\Console\Commands\BaseImportCommand;
use Domain\MemoryArticles\Models\MemoryArticle;

class ImportMemoryArticles extends BaseImportCommand
{
    protected $signature = 'import:memory-articles';

    protected $description = 'Import data for memory articles';

    public function handle()
    {
        $files = File::files(storage_path('import-data/mem'));

        foreach ($files as $file) {
            $file = str_replace(storage_path('import-data'), '', $file);
            $file = ltrim($file, '/');
            $fileName = str_replace('mem/', '', $file);

            $data = self::getFileData($file);
            $document = self::getDomDocumentWithXml($data);

            $modelData = [];
            $modelData['source_file'] = $fileName;
            $modelData['valley_id'] = str_replace('.xml', '', $fileName);

            $modelData['keywords'] = self::getKeywords($document);
            $modelData['county'] = preg_match('/^FM(\d+)\.xml$/', $fileName) ? 'franklin' : 'augusta';
            $modelData['title'] = self::getFirstElementValueByTagName($document, 'title');
            $modelData['author'] = self::getFirstElementValueByTagName($document, 'author');

            $creationElement = self::getFirstElementByTagName($document, 'creation');
            $dateValue = self::getFirstElementValueByTagName($creationElement, 'date');
            $modelData['date'] = !empty($dateValue)
                ? self::getformattedDate(str_pad($dateValue, 10, '-01'))
                : null;

            $bodyElement = self::getFirstElementByTagName($document, 'body');
            $bodyDivElement = self::getFirstElementByTagName($bodyElement, 'div1');
            $modelData['body'] = self::getElementHtml($document, $bodyDivElement, ['div1']);

            $frontElement = self::getFirstElementByTagName($document, 'front');
            $summaryElement = self::getFirstElementWithAttribute($frontElement, 'div1', 'type', 'summary');
            $modelData['summary'] = self::getElementHtml($document, $summaryElement, ['div1', 'p']);

            MemoryArticle::create($modelData);
            $this->info('Imported memory article data (' . $fileName . ')');
        }
    }
}

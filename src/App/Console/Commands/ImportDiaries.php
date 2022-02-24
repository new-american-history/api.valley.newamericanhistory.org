<?php

namespace App\Console\Commands;

use Domain\Shared\Models\Note;
use Domain\Shared\Models\Image;
use Domain\Papers\Models\Diary;
use Domain\Papers\Models\DiaryEntry;
use Illuminate\Support\Facades\File;
use App\Console\Commands\BaseImportCommand;

class ImportDiaries extends BaseImportCommand
{
    protected $signature = 'import:diaries';

    protected $description = 'Import data for diaries';

    protected $fileName;

    protected $document;

    protected $diary;

    public function handle()
    {
        $files = File::files(storage_path('import-data/papers'));

        foreach ($files as $file) {
            $file = str_replace(storage_path('import-data'), '', $file);
            $file = ltrim($file, '/');

            $fileName = str_replace('papers/', '', $file);
            $this->fileName = $fileName;

            $isDiary = preg_match('/^(AD(\d+)\.xml|BD4000\.xml|EmeDiar\.xml|FD(\d+)\.xml)$/', $fileName);

            if ($isDiary) {
                $data = self::getFileData($file);
                $this->document = self::getDomDocumentWithXml($data);

                self::handleDiary();
                self::handleDiaryEntries();
                self::handleNotes();
                self::handleImages();

                $this->info('Imported diary data (' . $fileName . ')');
            }
        }
    }

    protected function handleDiary()
    {
        $document = $this->document;
        $modelData = [];
        $modelData['source_file'] = $this->fileName;
        $modelData['valley_id'] = str_replace('.xml', '', $this->fileName);

        $modelData['keywords'] = self::getKeywords($document);
        $modelData['county'] = preg_match('/^FD(\d+)\.xml$/', $this->fileName) ? 'franklin' : 'augusta';
        $modelData['title'] = self::getFirstElementValueByTagName($document, 'title');
        $modelData['author'] = self::getFirstElementValueByTagName($document, 'author');
        $modelData['bio'] = self::getBio();

        $dateRangeElement = self::getFirstElementByTagName($document, 'dateRange');
        $modelData['start_date'] = !empty($dateRangeElement)
            ? self::getFormattedDate($dateRangeElement->getAttribute('from'))
            : null;
        $modelData['end_date'] = !empty($dateRangeElement)
            ? self::getFormattedDate($dateRangeElement->getAttribute('to'))
            : null;

        $this->diary = Diary::create($modelData);
    }

    protected function handleDiaryEntries()
    {
        $bodyElement = self::getFirstElementByTagName($this->document, 'body');
        $currentWeight = self::createDiaryEntries($bodyElement->getElementsByTagName('div1'));
        $currentWeight = self::createDiaryEntries($bodyElement->getElementsByTagName('div2'), $currentWeight);
        $currentWeight = self::createDiaryEntries($bodyElement->getElementsByTagName('div3'), $currentWeight);
    }

    protected function handleNotes()
    {
        $possibleNoteElements = $this->document->getElementsByTagName('div1');

        foreach ($possibleNoteElements as $possibleNoteElement) {
            if (self::elementHasAttribute($possibleNoteElement, 'type', 'notes')) {
                $noteIds = self::createNotes($possibleNoteElement->getElementsByTagName('div2'));
                $this->diary->notes()->sync($noteIds);
                break;
            }
        }
    }

    protected function handleImages()
    {
        $imageIds = self::createImages();
        $this->diary->images()->sync($imageIds);
    }

    protected function createDiaryEntries($nodeList, $weight = 0)
    {
        foreach ($nodeList as $node) {
            if (self::elementHasAttribute($node, 'type', ['entry', 'introduction'])) {
                $modelData = [];
                $modelData['diary_id'] = $this->diary->id;
                $modelData['weight'] = $weight;
                $modelData['date'] = self::getFormattedDate($node->getAttribute('n')) ?: null;

                $headElement = self::getFirstElementByTagName($node, 'head');
                $modelData['headline'] = self::getElementValue($headElement);

                self::removeChildElement($node, $headElement);

                $modelData['body'] = self::getElementHtml($this->document, $node, ['div\d']);
                DiaryEntry::create($modelData);

                $weight++;
            }
        }
        return $weight;
    }

    protected function createImages()
    {
        $internalSubset = $this->document->doctype->internalSubset;
        $imageMatches = [];
        preg_match_all('/<!ENTITY (.*) SYSTEM (.*) uvaHighRes>/', $internalSubset, $imageMatches);
        $imageNames = $imageMatches[1] ?? null;

        if (!empty($imageNames)) {
            $imageIds = [];
            $weight = 0;

            foreach ($imageNames as $imageName) {
                $path = 'papers/' . $imageName . '.jpg';
                $image = Image::firstOrCreate(['path' => $path]);

                $imageIds[$image->id] = ['weight' => $weight];
                $weight ++;
            }
            return $imageIds;
        }
        return null;
    }

    protected function createNotes($nodeList)
    {
        $noteIds = [];

        foreach ($nodeList as $node) {
            if (self::elementHasAttribute($node, 'type', 'section')) {
                $modelData = [];
                $modelData['number'] = $node->getAttribute('n') ?: null;

                $headElement = self::getFirstElementByTagName($node, 'head');
                $modelData['headline'] = self::getElementValue($headElement);

                self::removeChildElement($node, $headElement);

                $modelData['body'] = self::getElementHtml($this->document, $node, ['div2']);

                $note = Note::create($modelData);
                $noteIds[] = $note->id;
            }
        }
        return $noteIds;
    }

    protected function getBio()
    {
        $frontElement = self::getFirstElementByTagName($this->document, 'front');
        $frontDivElement = !empty($frontElement) ? self::getFirstElementByTagName($frontElement, 'div1') : null;

        if (self::elementHasAttribute($frontDivElement, 'type', 'bio')) {
            $frontHeadElement = self::getFirstElementByTagName($frontDivElement, 'head');

            self::removeChildElement($frontDivElement, $frontHeadElement);
            return self::getElementHtml($this->document, $frontDivElement, ['div1']);
        }
        return null;
    }
}

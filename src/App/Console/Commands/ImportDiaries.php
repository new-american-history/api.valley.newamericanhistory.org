<?php

namespace App\Console\Commands;

use Domain\Papers\Models\Note;
use Domain\Images\Models\Image;
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

                static::handleDiary();
                static::handleDiaryEntries();
                static::handleNotes();
                static::handleImages();

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

        $modelData['keywords'] = static::getKeywords($document);
        $modelData['county'] = preg_match('/^FD(\d+)\.xml$/', $this->fileName) ? 'franklin' : 'augusta';
        $modelData['title'] = static::getFirstElementValueByTagName($document, 'title');
        $modelData['author'] = static::getFirstElementValueByTagName($document, 'author');
        $modelData['bio'] = static::getBio();

        $dateRangeElement = static::getFirstElementByTagName($document, 'dateRange');
        $modelData['start_date'] = !empty($dateRangeElement)
            ? static::getFormattedDate($dateRangeElement->getAttribute('from'))
            : null;
        $modelData['end_date'] = !empty($dateRangeElement)
            ? static::getFormattedDate($dateRangeElement->getAttribute('to'))
            : null;

        $this->diary = Diary::create($modelData);
    }

    protected function handleDiaryEntries()
    {
        $bodyElement = static::getFirstElementByTagName($this->document, 'body');
        $currentWeight = static::createDiaryEntries($bodyElement->getElementsByTagName('div1'));
        $currentWeight = static::createDiaryEntries($bodyElement->getElementsByTagName('div2'), $currentWeight);
        $currentWeight = static::createDiaryEntries($bodyElement->getElementsByTagName('div3'), $currentWeight);
    }

    protected function handleNotes()
    {
        $possibleNoteElements = $this->document->getElementsByTagName('div1');

        foreach ($possibleNoteElements as $possibleNoteElement) {
            if (static::elementHasAttribute($possibleNoteElement, 'type', 'notes')) {
                $noteIds = static::createNotes($possibleNoteElement->getElementsByTagName('div2'));
                $this->diary->notes()->sync($noteIds);
                break;
            }
        }
    }

    protected function handleImages()
    {
        $imageIds = static::createImages();
        $this->diary->images()->sync($imageIds);
    }

    protected function createDiaryEntries($nodeList, $weight = 0)
    {
        foreach ($nodeList as $node) {
            if (static::elementHasAttribute($node, 'type', ['entry', 'introduction'])) {
                $modelData = [];
                $modelData['diary_id'] = $this->diary->id;
                $modelData['weight'] = $weight;
                $modelData['date'] = static::getFormattedDate($node->getAttribute('n')) ?: null;

                $headElement = static::getFirstElementByTagName($node, 'head');
                $modelData['headline'] = static::getElementValue($headElement);

                static::removeChildElement($node, $headElement);

                $body = $this->document->saveHTML($node);
                $body = static::removeTags($body, 'div\d');
                $body = static::getNormalizedValue($body);
                $modelData['body'] = $body;

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
            if (static::elementHasAttribute($node, 'type', 'section')) {
                $modelData = [];
                $modelData['number'] = $node->getAttribute('n') ?: null;

                $headElement = static::getFirstElementByTagName($node, 'head');
                $modelData['headline'] = static::getElementValue($headElement);

                static::removeChildElement($node, $headElement);

                $body = $this->document->saveHTML($node);
                $body = static::removeTags($body, 'div2');
                $body = static::getNormalizedValue($body);
                $modelData['body'] = $body;

                $note = Note::create($modelData);
                $noteIds[] = $note->id;
            }
        }
        return $noteIds;
    }

    protected function getBio()
    {
        $frontElement = static::getFirstElementByTagName($this->document, 'front');
        $frontDivElement = !empty($frontElement) ? static::getFirstElementByTagName($frontElement, 'div1') : null;

        if (static::elementHasAttribute($frontDivElement, 'type', 'bio')) {
            $frontHeadElement = static::getFirstElementByTagName($frontDivElement, 'head');

            static::removeChildElement($frontDivElement, $frontHeadElement);

            $bio = $this->document->saveHTML($frontDivElement);
            $body = static::removeTags($body, 'div1');
            $bio = static::getNormalizedValue($bio);
            return $bio;
        }
        return null;
    }
}

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
                static::handleDiaryNotes();
                static::handleDiaryImages();

                $this->info('Imported diary data (' . $fileName . ')');
            }
        }
    }

    protected function handleDiary()
    {
        $document = $this->document;
        $modelData = [];
        $modelData['source_file'] = $this->fileName;

        $keywords = static::getKeywordsAsArray($document);
        $modelData['keywords'] = json_encode($keywords) ?: null;

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

    protected function handleDiaryNotes()
    {
        $possibleNoteElements = $this->document->getElementsByTagName('div1');

        foreach ($possibleNoteElements as $possibleNoteElement) {
            if ($possibleNoteElement->getAttribute('type') === 'notes') {
                $noteIds = static::createNotes($possibleNoteElement->getElementsByTagName('div2'));
                $this->diary->notes()->sync($noteIds);
                break;
            }
        }
    }

    protected function handleDiaryImages()
    {
        $imageIds = static::createImages();
        $this->diary->images()->sync($imageIds);
    }

    protected function createDiaryEntries($nodeList, $weight = 0)
    {
        foreach ($nodeList as $node) {
            if (in_array($node->getAttribute('type'), ['entry', 'introduction'])) {
                $modelData = [];
                $modelData['diary_id'] = $this->diary->id;
                $modelData['weight'] = $weight;
                $modelData['date'] = static::getFormattedDate($node->getAttribute('n')) ?: null;

                $headElement = static::getFirstElementByTagName($node, 'head');
                $modelData['headline'] = static::getElementValue($headElement);

                if (!empty($headElement)) {
                    $node->removeChild($headElement);
                }

                $body = $this->document->saveHTML($node);
                $body = preg_replace('/<\/?div\d(.*)>/', '', $body);
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
            if ($node->getAttribute('type') === 'section') {
                $modelData = [];
                $modelData['number'] = $node->getAttribute('n') ?: null;

                $headElement = static::getFirstElementByTagName($node, 'head');
                $modelData['headline'] = static::getElementValue($headElement);

                if (!empty($headElement)) {
                    $node->removeChild($headElement);
                }

                $body = $this->document->saveHTML($node);
                $body = preg_replace('/<\/?div2(.*)>/', '', $body);
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

        if (!empty($frontDivElement) && $frontDivElement->getAttribute('type') === 'bio') {
            $frontHeadElement = static::getFirstElementByTagName($frontDivElement, 'head');

            if (!empty($frontHeadElement)) {
                $frontDivElement->removeChild($frontHeadElement);
            }

            $bio = $this->document->saveHTML($frontDivElement);
            $bio = preg_replace('/<\/?div1(.*)>/', '', $bio);
            $bio = static::getNormalizedValue($bio);
            return $bio;
        }
        return null;
    }

    public function getFormattedDate($value) {
        $value = str_replace('xx', '01', $value);
        $dateTime = strtotime($value);
        return !empty($dateTime) ? date('Y-m-d', $dateTime) : null;
    }
}

<?php

namespace App\Console\Commands;

use Domain\Shared\Models\Note;
use Domain\Shared\Models\Image;
use Domain\Papers\Models\Letter;
use Illuminate\Support\Facades\File;
use App\Console\Commands\BaseImportCommand;

class ImportLetters extends BaseImportCommand
{
    protected $signature = 'import:letters';

    protected $description = 'Import data for letters';

    protected $fileName;

    protected $document;

    protected $letter;

    public function handle()
    {
        $files = File::files(storage_path('import-data/papers'));

        foreach ($files as $file) {
            $file = str_replace(storage_path('import-data'), '', $file);
            $file = ltrim($file, '/');
            $fileName = str_replace('papers/', '', $file);
            $this->fileName = $fileName;

            $isDiary = preg_match('/^(AD(\d+)\.xml|BD4000\.xml|EmeDiar\.xml|FD(\d+)\.xml)$/', $fileName);

            if (!$isDiary) {
                $data = self::getFileData($file);
                $this->document = self::getDomDocumentWithXml($data);

                self::handleLetter();
                self::handleNotes();
                self::handleImages();

                $this->info('Imported letter data (' . $fileName . ')');
            }
        }
    }

    protected function handleLetter()
    {
        $document = $this->document;

        $frontElement = self::getFirstElementByTagName($document, 'front');
        $bodyElement = self::getFirstElementByTagName($document, 'body');
        $bodyDivElements = self::getElementsWithAttribute(
            $bodyElement,
            'div1',
            'type',
            ['letter', 'statement', 'contract', 'testimony', 'report', 'section']
        );

        foreach ($bodyDivElements as $index => $bodyDivElement) {
            $modelData = [];
            $modelData['source_file'] = '/papers/' . $this->fileName;
            $modelData['valley_id'] = str_replace('.xml', '', $this->fileName) . ($index !== 0 ? '-' . ($index + 1) : '');

            $modelData['keywords'] = self::getKeywords($document);
            $modelData['county'] = preg_match('/^FN?(\d+)\.xml$/', $this->fileName) ? 'franklin' : 'augusta';
            $modelData['title'] = self::getFirstElementValueByTagName($document, 'title');
            $modelData['author'] = self::getFirstElementValueByTagName($document, 'author');
            $modelData['valley_notes'] = self::getValleyNotes();

            $headElement = self::getFirstElementByTagName($bodyDivElement, 'head');
            $openerElement = self::getFirstElementByTagName($bodyElement, 'opener');
            $closerElement = self::getFirstElementByTagName($bodyElement, 'closer');

            if (preg_match('/^freedm/i', $modelData['title'])) {
                $modelData['collection'] = 'freedmens-bureau';
            }

            if (!empty($frontElement)) {
                $summaryElement = self::getFirstElementWithAttribute($frontElement, 'div1', 'type', 'summary');
                $modelData['summary'] = self::getElementValue($summaryElement);
            }

            if (!empty($headElement)) {
                $modelData['headline'] = self::getElementHtml($document, $headElement, ['head', 'name']);

                $recipientElement = self::getFirstElementWithAttribute($headElement, 'name', 'type', 'recipient');
                $modelData['recipient'] = self::getElementHtml($document, $recipientElement, ['name', 'p']);

                self::removeChildElement($bodyDivElement, $headElement);
            }

            if (!empty($openerElement)) {
                $dateElement = self::getFirstElementByTagName($openerElement, 'date');
                $modelData['date'] = !empty($dateElement)
                    ? self::getFormattedDate($dateElement->getAttribute('value'))
                    : null;
                $modelData['dateline'] = self::getElementHtml($document, $dateElement, ['date', 'p']);

                $openerSaluteElement = self::getFirstElementByTagName($openerElement, 'salute');
                $modelData['opening_salutation'] = self::getElementHtml($document, $openerSaluteElement, ['salute', 'p']);

                $locationElement = self::getFirstElementWithAttribute($openerElement, 'name', 'type', 'place');
                $modelData['location'] = self::getElementHtml($document, $locationElement, ['name', 'p']);

                self::removeChildElement($bodyDivElement, $openerElement);
            }

            if (!empty($closerElement)) {
                $closerSaluteElement = self::getFirstElementByTagName($closerElement, 'salute');
                $modelData['closing_salutation'] = self::getElementHtml($document, $closerSaluteElement, ['salute', 'p']);

                $signedElement = self::getFirstElementByTagName($closerElement, 'signed');
                $modelData['signed'] = self::getElementHtml($document, $signedElement, ['signed']);

                $postscriptElement = self::getFirstElementWithAttribute($closerElement, 'seg', 'type', 'postscript');
                $modelData['postscript'] = self::getElementHtml($document, $postscriptElement, ['postscript']);

                self::removeChildElement($bodyDivElement, $closerElement);
            }

            $epigraphElement = self::getFirstElementWithAttribute($bodyElement, 'div1', 'type', 'epigraph');
            $modelData['epigraph'] = self::getElementHtml($document, $epigraphElement, ['div1', 'p']);

            $modelData['body'] = self::getElementHtml($document, $bodyDivElement, ['div\d']);
            $this->letter = Letter::create($modelData);
        }
    }

    protected function handleNotes()
    {
        $possibleNoteElements = $this->document->getElementsByTagName('div1');

        foreach ($possibleNoteElements as $possibleNoteElement) {
            if (self::elementHasAttribute($possibleNoteElement, 'type', 'notes')) {
                $noteIds = self::createNotes($possibleNoteElement->getElementsByTagName('note'));

                $this->letter->notes()->sync($noteIds);
                break;
            }
        }
    }

    protected function handleImages()
    {
        $imageIds = self::createImages();
        $this->letter->images()->sync($imageIds);
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
            $modelData = [];
            $modelData['number'] = $node->getAttribute('n') ?: null;
            $modelData['body'] = self::getElementHtml($this->document, $node, ['note']);

            $note = Note::create($modelData);
            $noteIds[] = $note->id;
        }
        return $noteIds;
    }

    public function getValleyNotes() {
        $notesStmtElements = $this->document->getElementsByTagName('notesStmt');

        foreach ($notesStmtElements as $notesStmtElement) {
            $notesElement = self::getFirstElementByTagName($notesStmtElement, 'note');
            return self::getElementValue($notesElement);
        }
        return null;
    }
}

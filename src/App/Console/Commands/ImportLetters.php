<?php

namespace App\Console\Commands;

use Domain\Papers\Shared\Note;
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

                static::handleLetter();
                static::handleNotes();
                static::handleImages();

                $this->info('Imported letter data (' . $fileName . ')');
            }
        }
    }

    protected function handleLetter()
    {
        $document = $this->document;
        $modelData = [];
        $modelData['source_file'] = $this->fileName;
        $modelData['valley_id'] = str_replace('.xml', '', $this->fileName);

        $modelData['keywords'] = static::getKeywords($document);
        $modelData['county'] = preg_match('/^FN?(\d+)\.xml$/', $this->fileName) ? 'franklin' : 'augusta';
        $modelData['title'] = static::getFirstElementValueByTagName($document, 'title');
        $modelData['author'] = static::getFirstElementValueByTagName($document, 'author');
        $modelData['epigraph'] = static::getEpigraph();
        $modelData['valley_notes'] = static::getValleyNotes();

        $frontElement = static::getFirstElementByTagName($document, 'front');
        $bodyElement = static::getFirstElementByTagName($document, 'body');
        $bodyDivElement = static::getBodyDivElement();
        $headElement = static::getFirstElementByTagName($bodyElement, 'head');
        $openerElement = static::getFirstElementByTagName($bodyElement, 'opener');
        $closerElement = static::getFirstElementByTagName($bodyElement, 'closer');

        if (!empty($frontElement)) {
            $possibleSummaryElement = static::getFirstElementByTagName($frontElement, 'div1');

            if (static::elementHasAttribute($possibleSummaryElement, 'type', 'summary')) {
                $modelData['summary'] = static::getElementValue($possibleSummaryElement);
            }
        }

        if (!empty($headElement)) {
            $modelData['headline'] = static::getElementValue($headElement);
            $possibleRecipientElement = static::getFirstElementByTagName($headElement, 'name');

            if (static::elementHasAttribute($possibleRecipientElement, 'type', 'recipient')) {
                $modelData['recipient'] = static::getElementValue($possibleRecipientElement);
            }

            static::removeChildElement($bodyDivElement, $headElement);
        }

        if (!empty($openerElement)) {
            $dateElement = static::getFirstElementByTagName($openerElement, 'date');
            $modelData['date'] = !empty($dateElement)
                ? static::getFormattedDate($dateElement->getAttribute('value'))
                : null;
            $modelData['dateline'] = !empty($dateElement)
                ? static::getElementValue($dateElement)
                : null;
            $openerSaluteElement = static::getFirstElementByTagName($openerElement, 'salute');
            $modelData['opening_salutation'] = static::getElementValue($openerSaluteElement);
            $possibleLocationElement = static::getFirstElementByTagName($openerElement, 'name');

            if (static::elementHasAttribute($possibleLocationElement, 'type', 'place')) {
                $modelData['location'] = static::getElementValue($possibleLocationElement);
            }

            static::removeChildElement($bodyDivElement, $openerElement);
        }

        if (!empty($closerElement)) {
            $closerSaluteElement = static::getFirstElementByTagName($closerElement, 'salute');
            $modelData['closing_salutation'] = static::getElementValue($closerSaluteElement);
            $signedElement = static::getFirstElementByTagName($closerElement, 'signed');
            $modelData['signed'] = static::getElementValue($signedElement);
            $possiblePostscriptElement = static::getFirstElementByTagName($closerElement, 'seg');

            if (static::elementHasAttribute($possiblePostscriptElement, 'type', 'postscript')) {
                $modelData['postscript'] = static::getElementValue($possiblePostscriptElement);
            }

            static::removeChildElement($bodyDivElement, $closerElement);
        }

        $modelData['body'] = static::getBody($bodyDivElement);
        $this->letter = Letter::create($modelData);
    }

    protected function handleNotes()
    {
        $possibleNoteElements = $this->document->getElementsByTagName('div1');

        foreach ($possibleNoteElements as $possibleNoteElement) {
            if (static::elementHasAttribute($possibleNoteElement, 'type', 'notes')) {
                $noteIds = static::createNotes($possibleNoteElement->getElementsByTagName('note'));

                $this->letter->notes()->sync($noteIds);
                break;
            }
        }
    }

    protected function handleImages()
    {
        $imageIds = static::createImages();
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

            $headElement = static::getFirstElementByTagName($node, 'head');
            $modelData['headline'] = static::getElementValue($headElement);

            if (!empty($headElement)) {
                $node->removeChild($headElement);
            }

            $body = $this->document->saveHTML($node);
            $body = static::removeTags($body, 'note');
            $body = static::getNormalizedValue($body);
            $modelData['body'] = $body;

            $note = Note::create($modelData);
            $noteIds[] = $note->id;
        }
        return $noteIds;
    }

    public function getBody($bodyDivElement)
    {
        $body = $this->document->saveHTML($bodyDivElement);
        $body = static::removeTags($body, 'div\d');
        $body = static::getNormalizedValue($body);
        return $body;
    }

    public function getBodyDivElement() {
        $bodyElement = static::getFirstElementByTagName($this->document, 'body');
        $possibleBodyDivElements = $bodyElement->getElementsByTagName('div1');
        $possibleBodyDivTypes = ['letter', 'statement', 'contract', 'testimony', 'report', 'section'];

        foreach ($possibleBodyDivElements as $possibleBodyDivElement) {
            if (static::elementHasAttribute($possibleBodyDivElement, 'type', $possibleBodyDivTypes)) {
                return $possibleBodyDivElement;
            }
        }
        return null;
    }

    public function getEpigraph()
    {
        $bodyElement = static::getFirstElementByTagName($this->document, 'body');
        $possibleEpigraphElements = $bodyElement->getElementsByTagName('div1');

        foreach ($possibleEpigraphElements as $possibleEpigraphElement) {
            if (static::elementHasAttribute($possibleEpigraphElement, 'type', 'epigraph')) {
                return static::getElementValue($possibleEpigraphElement);
            }
        }
        return null;
    }

    public function getValleyNotes() {
        $notesStmtElements = $this->document->getElementsByTagName('notesStmt');

        foreach ($notesStmtElements as $notesStmtElement) {
            $notesElement = static::getFirstElementByTagName($notesStmtElement, 'note');
            if (!empty($notesElement)) {
                return static::getElementValue($notesElement);
            }
        }
        return null;
    }
}

<?php

namespace App\Console\Commands;

use Domain\Shared\Models\Note;
use Illuminate\Support\Facades\File;
use App\Console\Commands\BaseImportCommand;
use Domain\BattlefieldCorrespondence\Models\BattlefieldCorrespondence;

class ImportBattlefieldCorrespondence extends BaseImportCommand
{
    protected $signature = 'import:battlefield-correspondence';

    protected $description = 'Import data for battlefield correspondence';

    protected $fileName;

    protected $document;

    protected $battlefield_correspondence;

    public function handle()
    {
        $files = File::files(storage_path('import-data/or'));

        foreach ($files as $file) {
            $file = str_replace(storage_path('import-data'), '', $file);
            $file = ltrim($file, '/');
            $fileName = str_replace('or/', '', $file);
            $this->fileName = $fileName;

            $data = self::getFileData($file);
            $document = self::getDomDocumentWithXml($data);
            $this->document = $document;

            self::handleBattlefieldCorrespondence();
            self::handleNotes();

            $this->info('Imported battlefield correspondence data (' . $fileName . ')');
        }
    }

    protected function handleBattlefieldCorrespondence()
    {
        $document = $this->document;

        $modelData = [];
        $modelData['source_file'] = $this->fileName;
        $modelData['valley_id'] = str_replace('.xml', '', $this->fileName);

        $modelData['keywords'] = self::getKeywords($document);
        $modelData['title'] = self::getFirstElementValueByTagName($document, 'title');
        $modelData['author'] = self::getFirstElementValueByTagName($document, 'author');

        $possibleCountyAbbreviation = self::getFirstElementByTagName($document, 'TEI.2')->getAttribute('n') ?: null;
        if (!empty($possibleCountyAbbreviation)) {
            $modelData['county'] = $possibleCountyAbbreviation === 'au' ? 'augusta' : 'franklin';
        }

        $frontElement = self::getFirstElementByTagName($document, 'front');
        $bodyElement = self::getFirstElementByTagName($document, 'body');
        $bodyDivElement = self::getBodyDivElement();
        $headElement = self::getFirstElementByTagName($bodyElement, 'head');
        $openerElement = self::getFirstElementByTagName($bodyElement, 'opener');
        $closerElement = self::getFirstElementByTagName($bodyElement, 'closer');

        if (!empty($frontElement)) {
            $possibleSummaryElement = self::getFirstElementByTagName($frontElement, 'div1');

            if (self::elementHasAttribute($possibleSummaryElement, 'type', 'summary')) {
                $modelData['summary'] = self::getElementValue($possibleSummaryElement);
            }
        }

        if (!empty($headElement)) {
            $modelData['headline'] = self::getElementValue($headElement);
            $possibleRecipientElement = self::getFirstElementByTagName($headElement, 'name');

            if (self::elementHasAttribute($possibleRecipientElement, 'type', 'recipient')) {
                $modelData['recipient'] = self::getElementValue($possibleRecipientElement);
            }

            self::removeChildElement($bodyDivElement, $headElement);
        }

        if (!empty($openerElement)) {
            $dateElement = self::getFirstElementByTagName($openerElement, 'date');
            $modelData['date'] = !empty($dateElement)
                ? self::getFormattedDate($dateElement->getAttribute('value'))
                : null;
            $modelData['dateline'] = !empty($dateElement)
                ? self::getElementValue($dateElement)
                : null;
            $openerSaluteElement = self::getFirstElementByTagName($openerElement, 'salute');
            $modelData['opening_salutation'] = self::getElementValue($openerSaluteElement);
            $possibleLocationElement = self::getFirstElementByTagName($openerElement, 'name');

            if (self::elementHasAttribute($possibleLocationElement, 'type', 'place')) {
                $modelData['location'] = self::getElementValue($possibleLocationElement);
            }

            self::removeChildElement($bodyDivElement, $openerElement);
        }

        if (!empty($closerElement)) {
            $closerSaluteElement = self::getFirstElementByTagName($closerElement, 'salute');
            $modelData['closing_salutation'] = self::getElementValue($closerSaluteElement);
            $signedElement = self::getFirstElementByTagName($closerElement, 'signed');
            $modelData['signed'] = self::getElementValue($signedElement);
            $possiblePostscriptElement = self::getFirstElementByTagName($closerElement, 'seg');

            if (self::elementHasAttribute($possiblePostscriptElement, 'type', 'postscript')) {
                $modelData['postscript'] = self::getElementValue($possiblePostscriptElement);
            }

            self::removeChildElement($bodyDivElement, $closerElement);
        }

        $modelData['body'] = self::getBody($bodyDivElement);

        $battlefieldCorrespondence = BattlefieldCorrespondence::create($modelData);
        $this->battlefield_correspondence = $battlefieldCorrespondence;
    }

    protected function handleNotes()
    {
        $possibleNoteElements = $this->document->getElementsByTagName('div1');

        foreach ($possibleNoteElements as $possibleNoteElement) {
            if (self::elementHasAttribute($possibleNoteElement, 'type', 'notes')) {
                $noteIds = self::createNotes($possibleNoteElement->getElementsByTagName('note'));

                $this->battlefield_correspondence->notes()->sync($noteIds);
                break;
            }
        }
    }

    public function getBody($bodyDivElement)
    {
        $body = $this->document->saveHTML($bodyDivElement);
        $body = self::removeTags($body, 'div\d');
        $body = self::getNormalizedValue($body);
        return $body;
    }

    public function getBodyDivElement() {
        $bodyElement = self::getFirstElementByTagName($this->document, 'body');
        $possibleBodyDivElements = $bodyElement->getElementsByTagName('div1');

        foreach ($possibleBodyDivElements as $possibleBodyDivElement) {
            if (self::elementHasAttribute($possibleBodyDivElement, 'type', 'letter')) {
                return $possibleBodyDivElement;
            }
        }
        return null;
    }

    protected function createNotes($nodeList)
    {
        $noteIds = [];

        foreach ($nodeList as $node) {
            $modelData = [];
            $modelData['number'] = $node->getAttribute('n') ?: null;

            $headElement = self::getFirstElementByTagName($node, 'head');
            $modelData['headline'] = self::getElementValue($headElement);

            if (!empty($headElement)) {
                $node->removeChild($headElement);
            }

            $body = $this->document->saveHTML($node);
            $body = self::removeTags($body, 'note');
            $body = self::getNormalizedValue($body);
            $modelData['body'] = $body;

            $note = Note::create($modelData);
            $noteIds[] = $note->id;
        }
        return $noteIds;
    }
}

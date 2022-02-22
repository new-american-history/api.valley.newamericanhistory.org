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

            static::handleBattlefieldCorrespondence();
            static::handleNotes();

            $this->info('Imported battlefield correspondence data (' . $fileName . ')');
        }
    }

    protected function handleBattlefieldCorrespondence()
    {
        $document = $this->document;

        $modelData = [];
        $modelData['source_file'] = $this->fileName;
        $modelData['valley_id'] = str_replace('.xml', '', $this->fileName);

        $modelData['keywords'] = static::getKeywords($document);
        $modelData['title'] = static::getFirstElementValueByTagName($document, 'title');
        $modelData['author'] = static::getFirstElementValueByTagName($document, 'author');

        $possibleCountyAbbreviation = static::getFirstElementByTagName($document, 'TEI.2')->getAttribute('n') ?: null;
        if (!empty($possibleCountyAbbreviation)) {
            $modelData['county'] = $possibleCountyAbbreviation === 'au' ? 'augusta' : 'franklin';
        }

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

        $battlefieldCorrespondence = BattlefieldCorrespondence::create($modelData);
        $this->battlefield_correspondence = $battlefieldCorrespondence;
    }

    protected function handleNotes()
    {
        $possibleNoteElements = $this->document->getElementsByTagName('div1');

        foreach ($possibleNoteElements as $possibleNoteElement) {
            if (static::elementHasAttribute($possibleNoteElement, 'type', 'notes')) {
                $noteIds = static::createNotes($possibleNoteElement->getElementsByTagName('note'));

                $this->battlefield_correspondence->notes()->sync($noteIds);
                break;
            }
        }
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

        foreach ($possibleBodyDivElements as $possibleBodyDivElement) {
            if (static::elementHasAttribute($possibleBodyDivElement, 'type', 'letter')) {
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
}

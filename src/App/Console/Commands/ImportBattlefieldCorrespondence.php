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
        $bodyDivElement = self::getFirstElementWithAttribute($bodyElement, 'div1', 'type', 'letter');
        $headElement = self::getFirstElementByTagName($bodyElement, 'head');
        $openerElement = self::getFirstElementByTagName($bodyElement, 'opener');
        $closerElement = self::getFirstElementByTagName($bodyElement, 'closer');

        if (!empty($frontElement)) {
            $summaryElement = self::getFirstElementWithAttribute($frontElement, 'div1', 'type', 'summary');
            $modelData['summary'] = !empty($summaryElement) ? self::getElementValue($summaryElement) : null;
        }

        if (!empty($headElement)) {
            $modelData['headline'] = self::getElementValue($headElement);

            $recipientElement = self::getFirstElementWithAttribute($headElement, 'name', 'type', 'recipient');
            $modelData['recipient'] = !empty($recipientElement) ? self::getElementValue($recipientElement) : null;

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

            $locationElement = self::getFirstElementWithAttribute($openerElement, 'name', 'type', 'place');
            $modelData['location'] = !empty($locationElement) ? self::getElementValue($locationElement) : null;

            self::removeChildElement($bodyDivElement, $openerElement);
        }

        if (!empty($closerElement)) {
            $closerSaluteElement = self::getFirstElementByTagName($closerElement, 'salute');
            $modelData['closing_salutation'] = self::getElementValue($closerSaluteElement);
            $signedElement = self::getFirstElementByTagName($closerElement, 'signed');
            $modelData['signed'] = self::getElementValue($signedElement);

            $postscriptElement = self::getFirstElementWithAttribute($closerElement, 'seg', 'type', 'postscript');
            $modelData['postscript'] = !empty($postscriptElement) ? self::getElementValue($postscriptElement) : null;

            self::removeChildElement($bodyDivElement, $closerElement);
        }

        $modelData['body'] = self::getElementHtml($this->document, $bodyDivElement, ['div\d']);

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

            $modelData['body'] = self::getElementHtml($this->document, $node, ['note\d']);

            $note = Note::create($modelData);
            $noteIds[] = $note->id;
        }
        return $noteIds;
    }
}

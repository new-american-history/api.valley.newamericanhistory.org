<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\File;
use App\Console\Commands\BaseImportCommand;
use Domain\Claims\Models\SouthernClaimsCommissionClaim;
use Domain\Claims\Models\SouthernClaimsCommissionItem;
use Domain\Claims\Models\SouthernClaimsCommissionTestimony;

class ImportSouthernClaimsCommission extends BaseImportCommand
{
    protected $signature = 'import:southern-claims-commission';

    protected $description = 'Import data for Southern Claims Commission records';

    protected $fileName;

    protected $document;

    protected $claim;

    public function handle()
    {
        $files = File::files(storage_path('import-data/claims'));

        foreach ($files as $file) {
            $file = str_replace(storage_path('import-data'), '', $file);
            $file = ltrim($file, '/');
            $fileName = str_replace('claims/', '', $file);
            $this->fileName = $fileName;

            $data = self::getFileData($file);
            $document = self::getDomDocumentWithXml($data);
            $this->document = $document;

            self::handleClaim();
            self::handleItems();
            self::handleTestimonies();

            $this->info('Imported Southern Claims Commission data (' . $fileName . ')');
        }
    }

    protected function handleClaim()
    {
        $document = $this->document;

        $modelData = [];
        $modelData['county'] = 'augusta';
        $modelData['source_file'] = $this->fileName;
        $modelData['keywords'] = self::getKeywords($document);
        $modelData['title'] = self::getFirstElementValueByTagName($document, 'title');
        $modelData['author'] = self::getFirstElementValueByTagName($document, 'author');

        $frontElement = self::getFirstElementByTagName($document, 'front');
        $bodyElement = self::getFirstElementByTagName($document, 'body');
        $creationElement = self::getFirstElementByTagName($document, 'creation');
        $claimSummaryElement = self::getFirstElementWithAttribute($bodyElement, 'div1', 'type', 'claim_summary');

        $modelData['commission_summary'] = self::getElementHtml($this->document, $claimSummaryElement, ['div1']);

        $modelData['date'] = self::getFormattedDate(
            self::getFirstElementValueByTagName($creationElement, 'date') . '-01-01'
        );

        if (!empty($frontElement)) {
            $summaryElement = self::getFirstElementWithAttribute($frontElement, 'div1', 'type', 'summary');
            $modelData['summary'] = !empty($summaryElement) ? self::getElementValue($summaryElement) : null;
        }

        $claim = SouthernClaimsCommissionClaim::create($modelData);
        $this->claim = $claim;
    }

    protected function handleItems()
    {
        $claimItemsElement = self::getFirstElementWithAttribute($this->document, 'div1', 'type', 'claim_items');

        if (!empty($claimItemsElement)) {
            $rows = $claimItemsElement->getElementsByTagName('row');

            foreach ($rows as $rowIndex => $row) {
                if ($rowIndex !== 0) {
                    $modelData = [];
                    $modelData['claim_id'] = $this->claim->id;
                    $modelData['weight'] = $rowIndex - 1;

                    $cells = $row->getElementsByTagName('cell');

                    if (self::getElementValue($cells[0]) !== 'Total') {
                        foreach ($cells as $cellIndex => $cell) {
                            $value = self::getElementValue($cell);

                            switch ($cellIndex) {
                                case 0:
                                    $modelData['item'] = $value;
                                    break;
                                case 1:
                                    $modelData['amount_claimed'] = !empty($value) ? trim($value, '$') : '0.00';
                                    break;
                                case 2:
                                    $modelData['amount_allowed'] = !empty($value) ? trim($value, '$') : '0.00';
                                    break;
                                case 3:
                                    $modelData['amount_disallowed'] = !empty($value) ? trim($value, '$') : '0.00';
                                    break;
                                }
                        }

                        SouthernClaimsCommissionItem::create($modelData);
                    }
                }
            }
        }
    }

    protected function handleTestimonies()
    {
        $possibleClaimTestimonyElements = $this->document->getElementsByTagName('div1');

        foreach ($possibleClaimTestimonyElements as $index => $possibleClaimTestimonyElement) {
            if (self::elementHasAttribute($possibleClaimTestimonyElement, 'type', 'testimony')) {
                $modelData = [];
                $modelData['claim_id'] = $this->claim->id;
                $modelData['weight'] = $index;

                $bylineElement = self::getFirstElementByTagName($possibleClaimTestimonyElement, 'byline');
                $modelData['attestant'] = !empty($bylineElement) ? self::getElementValue($bylineElement) : null;

                self::removeChildElement($possibleClaimTestimonyElement, $bylineElement);
                $modelData['body'] = self::getElementHtml($this->document, $possibleClaimTestimonyElement, ['div1']);

                SouthernClaimsCommissionTestimony::create($modelData);
            }
        }
    }
}

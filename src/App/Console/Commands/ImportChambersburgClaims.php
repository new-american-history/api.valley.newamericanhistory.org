<?php

namespace App\Console\Commands;

use DateTime;
use Domain\Shared\Models\Image;
use App\Console\Commands\BaseImportCommand;
use Domain\Claims\Models\ChambersburgClaim;
use Domain\Claims\Models\ChambersburgClaimBuilding;

class ImportChambersburgClaims extends BaseImportCommand
{
    protected $signature = 'import:chambersburg-claims';

    protected $description = 'Import data for Chambersburg claims';

    protected $claimFile = 'data/claims.xml';

    protected $buildingFile = 'VoS/insurance/claim.html';

    protected $claimColumnMap = [
        'amount_award' => 'amount_awarded',
        'amount_rec' => 'amount_received',
        'bld_num' => 'building_number',
        'claim_num' => 'claim_number',
        'claim_total' => 'claim_total',
        'first' => 'first_name',
        'items' => 'items',
        'last' => 'last_name',
        'notes' => 'notes',
        'personal_property' => 'personal_property',
        'real_property' => 'real_property',
    ];

    public function handle()
    {
        self::handleClaims();
        self::handleBuildings();
    }

    public function handleClaims()
    {
        $data = self::getFileData($this->claimFile);
        $document = self::getDomDocumentWithXml($data);
        $items = $document->getElementsByTagName('row');

        foreach ($items as $item) {
            if (!empty($item)) {
                $modelData = [];
                $modelData['county'] = 'franklin';

                $columns = $item->getElementsByTagName('column');

                foreach ($columns as $column) {
                    $columnName = $column->getAttribute('name');
                    $value = self::getElementValue($column);

                    switch ($columnName) {
                        case 'claim_date':
                            $modelData['claim_date'] = self::getFormattedDate($value);
                            break;
                        case 'race':
                            $modelData['race'] = $value === 'b' ? 'black' : 'white';
                            break;
                        case 'sex':
                            $modelData['sex'] = $value === 'f' ? 'female' : 'male';
                            break;
                        default:
                           $modelAttribute = $this->claimColumnMap[$columnName] ?? null;
                            if (!empty($modelAttribute)) {
                                $modelData[$modelAttribute] = $value ?: null;
                            }
                            break;
                    }
                }

                ChambersburgClaim::create($modelData);
            }
        }

        $this->info('Imported Chambersburg claim data (' . $this->claimFile . ')');
    }

    public function handleBuildings()
    {
        $data = self::getFileData($this->buildingFile);
        $items = preg_split('/<hr \/>/', $data);

        foreach ($items as $index => $item) {
            if ($index !== 0 && !empty($item)) {
                $document = self::getDomDocumentWithHtml($item);

                if (!empty($document)) {
                    $modelData = [];

                    $titleElement = self::getFirstElementByTagName($document, 'a');
                    if (!empty($titleElement)) {
                        $titleValue = self::getElementValue($titleElement);
                        $titleValues = preg_split('/#|:/', $titleValue);

                        $modelData['building_number'] = trim(trim($titleValues[1], '?')) ?: null;
                        $modelData['name'] = trim(trim($titleValues[2], ',')) ?: null;

                        $imageFile = $titleElement->getAttribute('href');
                        if (!empty($imageFile)) {
                            $imageFileData = self::getImageFileData($imageFile);
                            $modelData = array_merge($modelData, $imageFileData);
                        }

                        $titleElement->parentNode->removeChild($titleElement);
                    }

                    $description = $document->saveHTML($document);
                    $description = self::removeTags($description, 'html');
                    $description = self::removeTags($description, 'body');
                    $description = self::getNormalizedValue($description);
                    $modelData['description'] = $description;

                    ChambersburgClaimBuilding::create($modelData);
                }
            }
        }

        $this->info('Imported Chambersburg claim data (' . $this->buildingFile . ')');
    }

    public function getFormattedDate($value)
    {
        if (!empty($value)) {
            $value = str_replace('00/', '01/', $value);
            $dateTime = DateTime::createFromFormat('m-d-y', $value);
            return !empty($dateTime) ? '18' . $dateTime->format('y-m-d') : null;
        }
    }

    protected function getImageFileData($fileName)
    {
        $data = self::getFileData('VoS/insurance/' . $fileName);
        $document = self::getDomDocumentWithHtml($data);

        $bodyElement = self::getFirstElementByTagName($document, 'body');
        $imageElement = self::getFirstElementByTagName($document, 'img');

        $imageFileData = [];
        $imageFileData['image_title'] = self::getFirstElementValueByTagName($document, 'title');
        $imageFileData['image_heading'] = self::getFirstElementValueByTagName($bodyElement, 'h1');

        $imageModelData = [];
        $imageModelData['alt'] = $imageElement->getAttribute('alt') ?? null;
        $imageModelData['width'] = $imageElement->getAttribute('width') ?? null;
        $imageModelData['height'] = $imageElement->getAttribute('height') ?? null;
        $path = 'claims/' . $imageElement->getAttribute('src');

        $image = Image::updateOrCreate(['path' => $path], $imageModelData);
        $imageFileData['image_id'] = $image->id;

        return $imageFileData;
    }
}

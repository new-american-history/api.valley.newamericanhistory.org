<?php

namespace App\Console\Commands;

use Domain\Shared\Models\Image;
use App\Console\Commands\BaseImportCommand;
use Domain\FireInsurancePolicies\Models\FireInsurancePolicy;

class ImportFireInsurancePolicies extends BaseImportCommand
{
    protected $signature = 'import:fire-insurance-policies';

    protected $description = 'Import data for fire insurance policies';

    protected $file = 'VoS/insurance/mas1860.html';

    public function handle()
    {
        $data = self::getFileData($this->file);
        $items = preg_split('/<hr \/>/', $data);

        foreach ($items as $index => $item) {
            if ($index !== 0 && !empty($item)) {
                $document = self::getDomDocumentWithHtml($item);

                if (!empty($document)) {
                    $modelData = [];
                    $modelData['county'] = 'augusta';

                    $linkElement = self::getFirstElementByTagName($document, 'a');

                    if (!empty($linkElement)) {
                        $path = 'claims/' . $linkElement->getAttribute('href');
                        $image = Image::firstOrCreate(['path' => $path]);
                        $modelData['image_id'] = $image->id;
                    }

                    $body = self::getElementHtml($document, $document, ['html', 'body', 'a']);

                    $matches = [];
                    preg_match('/Policy #(\w+), ([^<]*)<br\s?\/?>(.*)/', $body, $matches);

                    $modelData['policy_number'] = $matches[1] ?? null;
                    $modelData['name'] = trim($matches[2]) ?? null;
                    $modelData['description'] = trim($matches[3]) ?? null;

                    FireInsurancePolicy::create($modelData);
                }
            }
        }

        $this->info('Imported fire insurance policies (' . $this->file . ')');
    }
}

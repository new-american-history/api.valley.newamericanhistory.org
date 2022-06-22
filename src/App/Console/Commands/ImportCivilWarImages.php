<?php

namespace App\Console\Commands;

use Domain\Shared\Models\Image;
use Domain\CivilWarImages\Models\Subject;
use Domain\CivilWarImages\Enums\ImageType;
use App\Console\Commands\BaseImportCommand;
use Domain\CivilWarImages\Enums\OriginalSource;
use Domain\CivilWarImages\Models\Image as CivilWarImage;

class ImportCivilWarImages extends BaseImportCommand
{
    protected $signature = 'import:civil-war-images';

    protected $description = 'Import data for Civil War images';

    protected $file = 'data/images.xml';

    protected $columnMap = [
        'artist' => 'artist',
        'description' => 'description',
        'people_name' => 'person_name',
        'place_name' => 'location',
        'regiment' => 'regiment',
        'source' => 'contributing_source',
        'title' => 'title',
    ];

    public function handle()
    {
        $data = self::getFileData($this->file);
        $document = self::getDomDocumentWithXml($data);
        $items = $document->getElementsByTagName('row');

        foreach ($items as $item) {
            if (!empty($item)) {
                $modelData = [];
                $columns = $item->getElementsByTagName('column');

                foreach ($columns as $column) {
                    $columnName = $column->getAttribute('name');
                    $value = self::getElementValue($column, ['nd']);

                    switch ($columnName) {
                        case 'image_date':
                            $modelData['date'] = $value;
                            break;
                        case 'image_name':
                            if (!empty($value)) {
                                $value = str_ireplace('gif', 'jpg', $value);
                                $image = Image::firstOrCreate(['path' => $value]);
                                $modelData['image_id'] = $image->id;
                            }
                            break;
                        case 'image_type':
                            $value = self::getNormalizedString($value);
                            $value = $value === 'photo' ? 'photograph' : $value;
                            $imageTypeEnum = ImageType::tryFrom($value);
                            $modelData['image_type'] = $imageTypeEnum->value ?? null;
                            break;
                        case 'orig_location':
                            $value = self::getNormalizedString($value);
                            $value = str_replace('\'', '', $value);
                            $originalSourceEnum = OriginalSource::tryFrom($value);
                            $modelData['original_source'] = $originalSourceEnum->value ?? null;
                            break;
                        case 'subject_type':
                            if (!empty($value)) {
                                $subject = Subject::firstOrCreate(['name' => $value]);
                                $modelData['subject_id'] = $subject->id;
                            }
                            break;
                        default:
                            $modelAttribute = $this->columnMap[$columnName] ?? null;
                            if (!empty($modelAttribute)) {
                                $modelData[$modelAttribute] = $value;
                            }
                            break;
                    }
                }

                CivilWarImage::create($modelData);
            }
        }

        $this->info('Imported Civil War image data (' . $this->file . ')');
    }

    public function getNormalizedString($string) {
        $string = strtolower($string);
        $string = str_replace('’', "'", $string);
        $string = str_replace('?', '', $string);
        $string = preg_replace('/ (.?)/', strtoupper('$1'), $string);

        return $string;
    }
}

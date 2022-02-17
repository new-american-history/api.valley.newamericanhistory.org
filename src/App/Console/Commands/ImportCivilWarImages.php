<?php

namespace App\Console\Commands;

use Domain\Images\Models\Image;
use Domain\CivilWarImages\Models\Subject;
use App\Console\Commands\BaseImportCommand;
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

        $originalSourceMap = self::getNormalizedMap(config('domains.civil-war-images.originalSources'));
        $imageTypeMap = self::getNormalizedMap(config('domains.civil-war-images.imageTypes'));

        foreach ($items as $item) {
            if (!empty($item)) {
                $modelData = [];
                $columns = $item->getElementsByTagName('column');

                foreach ($columns as $column) {
                    $columnName = $column->getAttribute('name');
                    $value = static::getElementValue($column, ['nd']);

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

                            if ($value === 'photo') {
                                $modelData['image_type'] = 'photograph';
                            } else {
                                $key = array_search($value, $imageTypeMap);
                                $modelData['image_type'] = $key ?: null;
                            }
                            break;
                        case 'orig_location':
                            $value = self::getNormalizedString($value);
                            $key = array_search($value, $originalSourceMap);
                            $modelData['original_source'] = $key ?: null;
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
                                $modelData[$modelAttribute] = $value ?: null;
                            }
                            break;
                    }
                }

                CivilWarImage::create($modelData);
            }
        }

        $this->info('Imported Civil War image data (' . $this->file . ')');
    }

    public function getNormalizedMap($map) {
        $modifiedMap = [];

        foreach ($map as $key => $value) {
            $modifiedMap[$key] = self::getNormalizedString($value);
        }

        return $modifiedMap;
    }

    public function getNormalizedString($string) {
        $string = strtolower($string);
        $string = str_replace('’', "'", $string);
        $string = str_replace('?', '', $string);
        return $string;
    }
}

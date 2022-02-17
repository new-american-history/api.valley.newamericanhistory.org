<?php

namespace App\Console\Commands;

use Domain\Newspapers\Models\Name;
use Domain\Newspapers\Models\Page;
use Domain\Newspapers\Models\Story;
use Domain\Newspapers\Models\Topic;
use Illuminate\Support\Facades\File;
use Domain\Newspapers\Models\Edition;
use Domain\Newspapers\Models\Newspaper;
use App\Console\Commands\BaseImportCommand;

class ImportNewspapers extends BaseImportCommand
{
    protected $signature = 'import:newspapers';

    protected $description = 'Import data for newspapers';

    protected $fileName;

    protected $document;

    protected $newspaper;

    protected $edition;

    protected $chapter;

    protected $storyTypeMap = [
        'a' => 'article',
        'c' => 'classified',
        'e' => 'editorial',
        'j' => 'judicial',
        'l' => 'letter',
        'o' => 'obituary',
        'p' => 'poem',
        's' => 'society',

    ];

    public function handle()
    {
        static::handleNewspapers();
        static::handlePdfs();
        static::handleTopics();
    }

    protected function handleNewspapers()
    {
        $files = File::allFiles(storage_path('import-data/newspapers'));

        foreach ($files as $file) {
            $file = str_replace(storage_path('import-data'), '', $file);
            $file = ltrim($file, '/');
            $fileName = str_replace('newspapers/', '', $file);
            $this->fileName = $fileName;

            $data = self::getFileData($file);
            $this->document = self::getDomDocumentWithXml($data);

            static::handleNewspaper();
            static::handleEdition();
            static::handlePages();

            $this->info('Imported newspaper data (' . $fileName . ')');
        }
    }

    protected function handlePdfs()
    {
        $file = 'news/newspaper_pdf_catalog.xml';
        $data = self::getFileData($file);
        $document = self::getDomDocumentWithXml($data);

        $newspaperElements = $document->getElementsByTagName('div1');

        foreach ($newspaperElements as $newspaperElement) {
            $newspaperAbbreviation = $newspaperElement->getAttribute('n');
            $yearElements = $newspaperElement->getElementsByTagName('div2');

            foreach ($yearElements as $yearElement) {
                $year = $yearElement->getAttribute('n');
                $directory = $newspaperAbbreviation . $year;

                $pdfElements = $yearElement->getElementsByTagName('hwp');

                foreach ($pdfElements as $pdfElement) {
                    $fileName = $pdfElement->getAttribute('id');
                    $file = $directory . '/' . $fileName;
                    $editionSourceFile = str_replace('pdf', 'xml', $file);
                    $edition = Edition::where('source_file', $editionSourceFile)->first();

                    if (!empty($edition)) {
                        $edition->pdf = $file;
                        $edition->save();
                    }
                }
            }
        }

        $this->info('Imported newspaper PDF data');
    }

    protected function handleTopics()
    {
        $files = File::allFiles(storage_path('import-data/news'));

        foreach ($files as $file) {
            $file = str_replace(storage_path('import-data'), '', $file);
            $file = ltrim($file, '/');
            $fileName = str_replace('news/', '', $file);

            $isTopicsFile = preg_match('/Topics\.xml$/', $fileName);

            if ($isTopicsFile) {
                $data = self::getFileData($file);
                $document = self::getDomDocumentWithXml($data);

                $this->chapter = str_replace('Topics.xml', '', $fileName);

                $parentTopicElements = $document->getElementsByTagName('div1');

                foreach ($parentTopicElements as $parentTopicElement) {
                    $parentTopic = static::createTopic($parentTopicElement);

                    $childTopicElements = $parentTopicElement->getElementsByTagName('div3');

                    foreach ($childTopicElements as $childTopicElement) {
                        $childTopic = static::createTopic($childTopicElement, $parentTopic);

                        static::addStoriesToTopic($childTopic, $childTopicElement);
                        static::removeChildElement($childTopicElement->parentNode, $childTopicElement);
                    }

                    static::addStoriesToTopic($parentTopic, $parentTopicElement);
                }

                $this->info('Imported newspaper topic data (' . $fileName . ')');
            }
        }
    }

    protected function handleNewspaper()
    {
        $headerElement = static::getFirstElementByTagName($this->document, 'header');
        $name = static::getFirstElementValueByTagName($headerElement, 'title');
        $existingNewspaper = Newspaper::where('name', $name)->first();

        if (!empty($existingNewspaper)) {
            $this->newspaper = $existingNewspaper;
            return;
        }

        $modelData = [];
        $modelData['name'] = $name;
        $modelData['city'] = static::getFirstElementValueByTagName($headerElement, 'city');
        $modelData['frequency'] = static::getFirstElementValueByTagName($headerElement, 'frequency');

        if (preg_match('/\/va\.au/', $this->fileName)) {
            $modelData['county'] = 'augusta';
            $modelData['state'] = 'virginia';
        } elseif (preg_match('/\/pa\.fr/', $this->fileName)) {
            $modelData['county'] = 'franklin';
            $modelData['state'] = 'pennsylvania';
        }

        $this->newspaper = Newspaper::create($modelData);
    }

    protected function handleEdition()
    {
        $modelData = [];
        $modelData['newspaper_id'] = $this->newspaper->id;
        $modelData['source_file'] = $this->fileName;

        $headerElement = static::getFirstElementByTagName($this->document, 'header');
        $dateElement = static::getFirstElementByTagName($headerElement, 'date');

        $modelData['weekday'] = strtolower(static::getFirstElementValueByTagName($dateElement, 'weekday'));

        if (!empty($dateElement->getAttribute('n'))) {
            $modelData['date'] = $dateElement->getAttribute('n');
        } else {
            $year = static::getFirstElementValueByTagName($dateElement, 'year');
            $month = static::getFirstElementByTagName($dateElement, 'month')->getAttribute('norm');
            $day = static::getFirstElementByTagName($dateElement, 'day')->getAttribute('norm');
            $modelData['date'] = static::getFormattedDate("{$year}-{$month}-{$day}");
        }

        $bodyElement = static::getFirstElementByTagName($this->document, 'paperBody');
        $modelData['headline'] = static::getFirstElementValueByTagName($bodyElement, 'head');

        $this->edition = Edition::updateOrCreate(['source_file' => $modelData['source_file']], $modelData);
    }

    protected function handlePages()
    {
        $editionId = $this->edition->id;
        $pageElements = $this->document->getElementsByTagName('page');

        foreach ($pageElements as $pageElement) {
            $modelData = [];
            $modelData['newspaper_edition_id'] = $editionId;
            $modelData['number'] = preg_replace('/[^\d]/', '', $pageElement->getAttribute('n'));
            $modelData['description'] = static::getFirstElementValueByTagName($pageElement, 'pageNote');

            $page = Page::create($modelData);
            static::handleStories($pageElement, $page->id);
        }
    }

    protected function handleStories($pageElement, $pageId)
    {
        $storyElements = $pageElement->getElementsByTagName('div');

        foreach ($storyElements as $index => $storyElement) {
            $modelData = [];
            $modelData['newspaper_page_id'] = $pageId;
            $modelData['weight'] = $index;
            $modelData['column'] = ltrim(static::getFirstElementValueByTagName($storyElement, 'column'), '0');
            $modelData['headline'] = static::getFirstElementValueByTagName($storyElement, 'head');
            $modelData['summary'] = static::getFirstElementValueByTagName($storyElement, 'summary');
            $modelData['origin'] = static::getFirstElementValueByTagName($storyElement, 'origin');
            $modelData['excerpt'] = static::getFirstElementValueByTagName($storyElement, 'excerpt');
            $modelData['trailer'] = static::getFirstElementValueByTagName($storyElement, 'trailer');
            $modelData['commentary'] = static::getFirstElementValueByTagName($storyElement, 'commentary');

            $typeValue = $storyElement->getAttribute('type');
            $modelData['type'] = $this->storyTypeMap[$typeValue] ?? null;

            $bodyElement = static::getFirstElementByTagName($storyElement, 'transcript');
            $body = $this->document->saveHTML($bodyElement);
            $body = preg_replace('/<\/?transcript>/', '', $body);
            $body = static::getNormalizedValue($body);
            $modelData['body'] = $body;

            $story = Story::create($modelData);
            static::handleNames($storyElement, $story->id);
        }
    }

    protected function handleNames($storyElement, $storyId)
    {
        $namesElement = static::getFirstElementByTagName($storyElement, 'names');

        if (!empty($namesElement)) {
            $nameElements = $namesElement->getElementsByTagName('name');

            foreach ($nameElements as $index => $nameElement) {
                $modelData = [];
                $modelData['newspaper_story_id'] = $storyId;
                $modelData['weight'] = $index;
                $modelData['prefix'] = static::getFirstElementValueByTagName($storyElement, 'pf');
                $modelData['first_name'] = static::getFirstElementValueByTagName($storyElement, 'fn');
                $modelData['last_name'] = static::getFirstElementValueByTagName($storyElement, 'ln');
                $modelData['suffix'] = static::getFirstElementValueByTagName($storyElement, 'sf');

                Name::create($modelData);
            }
        }
    }

    protected function createTopic($topicElement, $parentTopic = null)
    {
        $name = static::getFirstElementValueByTagName($topicElement, 'head');

        if (empty($name)) {
            return $parentTopic;
        }

        $modelData = [];
        $modelData['chapter'] = $this->chapter;
        $modelData['name'] = static::getFirstElementValueByTagName($topicElement, 'head');
        $modelData['parent_id'] = $parentTopic->id ?? null;

        $topic = Topic::create($modelData);
        return $topic;
    }

    protected function addStoriesToTopic($topic, $topicElement)
    {
        $entryElements = $topicElement->getElementsByTagName('entry');
        $storyIds = [];

        foreach ($entryElements as $entryElement) {
            $xrefElement = static::getFirstElementByTagName($entryElement, 'xref');

            $link = $xrefElement->getAttribute('link');
            $editionFileName = preg_replace('/,.*$/', '', $link);

            $linkMatches = [];
            preg_match('/^\w+\.\w+\.(\w+)\.(\d+)\.\d+\.\d+$/', $editionFileName, $linkMatches);

            $newspaperAbbreviation = $linkMatches[1] ?? null;
            $year = $linkMatches[2] ?? null;

            if (!empty($newspaperAbbreviation) && !empty($year)) {
                $directory = $newspaperAbbreviation . $year;
                $editionSourceFile = $directory . '/' . $editionFileName . '.xml';
                $edition = Edition::where('source_file', $editionSourceFile)->first();

                if (!empty($edition)) {
                    $title = static::getElementValue($xrefElement);

                    $titleMatches = [];
                    preg_match('/\d\d\d\d, p\. (\d+), c\. (\d+)/', $title, $titleMatches);

                    $pageNumber = $titleMatches[1] ?? null;
                    $columnNumber = $titleMatches[2] ?? null;

                    $page = Page::where('newspaper_edition_id', $edition->id)
                        ->where('number', $pageNumber)
                        ->first();

                    if (!empty($page)) {
                        $story = Story::where('newspaper_page_id', $page->id)
                            ->where('column', $columnNumber)
                            ->first();

                        if (!empty($story)) {
                            $storyIds[] = $story->id;
                        }
                    }
                }
            }
        }

        $topic->stories()->sync($storyIds);
    }
}

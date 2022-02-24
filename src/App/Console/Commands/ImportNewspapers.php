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
        self::handleNewspapers();
        self::handlePdfs();
        self::handleTopics();
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

            self::handleNewspaper();
            self::handleEdition();
            self::handlePages();

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
                    $parentTopic = self::createTopic($parentTopicElement);

                    $childTopicElements = $parentTopicElement->getElementsByTagName('div3');

                    foreach ($childTopicElements as $childTopicElement) {
                        $childTopic = self::createTopic($childTopicElement, $parentTopic);

                        self::addStoriesToTopic($childTopic, $childTopicElement);
                        self::removeChildElement($childTopicElement->parentNode, $childTopicElement);
                    }

                    self::addStoriesToTopic($parentTopic, $parentTopicElement);
                }

                $this->info('Imported newspaper topic data (' . $fileName . ')');
            }
        }
    }

    protected function handleNewspaper()
    {
        $headerElement = self::getFirstElementByTagName($this->document, 'header');
        $name = self::getFirstElementValueByTagName($headerElement, 'title');
        $existingNewspaper = Newspaper::where('name', $name)->first();

        if (!empty($existingNewspaper)) {
            $this->newspaper = $existingNewspaper;
            return;
        }

        $modelData = [];
        $modelData['name'] = $name;
        $modelData['city'] = self::getFirstElementValueByTagName($headerElement, 'city');
        $modelData['frequency'] = self::getFirstElementValueByTagName($headerElement, 'frequency');

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

        $headerElement = self::getFirstElementByTagName($this->document, 'header');
        $dateElement = self::getFirstElementByTagName($headerElement, 'date');

        $modelData['weekday'] = strtolower(self::getFirstElementValueByTagName($dateElement, 'weekday'));

        if (!empty($dateElement->getAttribute('n'))) {
            $modelData['date'] = $dateElement->getAttribute('n');
        } else {
            $year = self::getFirstElementValueByTagName($dateElement, 'year');
            $month = self::getFirstElementByTagName($dateElement, 'month')->getAttribute('norm');
            $day = self::getFirstElementByTagName($dateElement, 'day')->getAttribute('norm');
            $modelData['date'] = self::getFormattedDate("{$year}-{$month}-{$day}");
        }

        $bodyElement = self::getFirstElementByTagName($this->document, 'paperBody');
        $modelData['headline'] = self::getFirstElementValueByTagName($bodyElement, 'head');

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
            $modelData['description'] = self::getFirstElementValueByTagName($pageElement, 'pageNote');

            $page = Page::create($modelData);
            self::handleStories($pageElement, $page->id);
        }
    }

    protected function handleStories($pageElement, $pageId)
    {
        $storyElements = $pageElement->getElementsByTagName('div');

        foreach ($storyElements as $index => $storyElement) {
            $modelData = [];
            $modelData['newspaper_page_id'] = $pageId;
            $modelData['weight'] = $index;
            $modelData['column'] = ltrim(self::getFirstElementValueByTagName($storyElement, 'column'), '0');
            $modelData['headline'] = self::getFirstElementValueByTagName($storyElement, 'head');
            $modelData['summary'] = self::getFirstElementValueByTagName($storyElement, 'summary');
            $modelData['origin'] = self::getFirstElementValueByTagName($storyElement, 'origin');
            $modelData['excerpt'] = self::getFirstElementValueByTagName($storyElement, 'excerpt');
            $modelData['trailer'] = self::getFirstElementValueByTagName($storyElement, 'trailer');
            $modelData['commentary'] = self::getFirstElementValueByTagName($storyElement, 'commentary');

            $typeValue = $storyElement->getAttribute('type');
            $modelData['type'] = $this->storyTypeMap[$typeValue] ?? null;

            $bodyElement = self::getFirstElementByTagName($storyElement, 'transcript');
            $body = $this->document->saveHTML($bodyElement);
            $body = self::removeTags($body, 'transcript');
            $body = self::getNormalizedValue($body);
            $modelData['body'] = $body;

            $story = Story::create($modelData);
            self::handleNames($storyElement, $story->id);
        }
    }

    protected function handleNames($storyElement, $storyId)
    {
        $namesElement = self::getFirstElementByTagName($storyElement, 'names');

        if (!empty($namesElement)) {
            $nameElements = $namesElement->getElementsByTagName('name');

            foreach ($nameElements as $index => $nameElement) {
                $modelData = [];
                $modelData['newspaper_story_id'] = $storyId;
                $modelData['weight'] = $index;
                $modelData['prefix'] = self::getFirstElementValueByTagName($storyElement, 'pf');
                $modelData['first_name'] = self::getFirstElementValueByTagName($storyElement, 'fn');
                $modelData['last_name'] = self::getFirstElementValueByTagName($storyElement, 'ln');
                $modelData['suffix'] = self::getFirstElementValueByTagName($storyElement, 'sf');

                Name::create($modelData);
            }
        }
    }

    protected function createTopic($topicElement, $parentTopic = null)
    {
        $name = self::getFirstElementValueByTagName($topicElement, 'head');

        if (empty($name)) {
            return $parentTopic;
        }

        $modelData = [];
        $modelData['chapter'] = $this->chapter;
        $modelData['name'] = self::getFirstElementValueByTagName($topicElement, 'head');
        $modelData['parent_id'] = $parentTopic->id ?? null;

        $topic = Topic::create($modelData);
        return $topic;
    }

    protected function addStoriesToTopic($topic, $topicElement)
    {
        $entryElements = $topicElement->getElementsByTagName('entry');
        $storyIds = [];

        foreach ($entryElements as $entryElement) {
            $xrefElement = self::getFirstElementByTagName($entryElement, 'xref');

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
                    $title = self::getElementValue($xrefElement);

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

<?php

namespace App\Console\Commands;

use DOMDocument;
use Illuminate\Console\Command;

class BaseImportCommand extends Command
{
    protected $signature = 'import';

    protected $description = 'Base class for data import commands';

    public function elementHasAttribute($element, $attribute, $value)
    {
        return is_array($value)
            ? !empty($element) && in_array($element->getAttribute($attribute), $value)
            : !empty($element) && $element->getAttribute($attribute) === $value;
    }

    public function getBoolean($value)
    {
        $value = trim($value);
        if ($value === 'yes' || $value === '1') {
            return true;
        }
        return false;
    }

    public function getDomDocumentWithHtml($html)
    {
        $errorsToSkip = ['Unexpected end tag : p'];
        return self::getDomDocument($html, 'loadHTML', $errorsToSkip);
    }

    public function getDomDocumentWithXml($xml)
    {
        return self::getDomDocument($xml, 'loadXML');
    }

    public function getDomDocument($data, $function, $errorsToSkip = [])
    {
        libxml_use_internal_errors(true);

        $document = new DOMDocument();
        $document->$function($data, LIBXML_HTML_NODEFDTD);

        foreach (libxml_get_errors() as $error) {
            if (!in_array(trim($error->message), $errorsToSkip)) {
                echo $error->message;
            }
        }
        libxml_use_internal_errors(false);

        return $document ?? null;
    }

    public function getElementValue($element)
    {
        $value = $element ? $element->nodeValue : null;
        return static::getNormalizedValue($value);
    }

    public function getFileData($file)
    {
        $path = storage_path() . '/import-data/' . $file;
        return file_get_contents($path);
    }

    public function getFirstElementByTagName($document, $tagName)
    {
        return $document->getElementsByTagName($tagName)->item(0) ?? null;
    }

    public function getFirstElementValueByTagName($document, $tagName)
    {
        $element = static::getFirstElementByTagName($document, $tagName);
        return static::getElementValue($element);
    }

    public function getFormattedDate($value) {
        $value = str_replace('?', '1', $value);
        $value = str_replace('xx', '01', $value);
        $dateTime = strtotime($value);
        return !empty($dateTime) ? date('Y-m-d', $dateTime) : null;
    }

    public function getKeywordsAsArray($document)
    {
        $elements = $document->getElementsByTagName('term') ?? [];
        $keywords = [];

        foreach ($elements as $element) {
            $value = static::getElementValue($element);

            if (!empty($value)) {
                $keywords[] = explode(', ', $value);
            }
        }

        return !empty($keywords) ? collect($keywords)->flatten()->toArray() : null;
    }

    public function getMonthAsInteger($value)
    {
        $value = trim($value);
        $dateTime = strtotime($value);
        return !empty($dateTime) ? date('m', $dateTime) : null;
    }

    public function getNormalizedValue($value)
    {
        $value = preg_replace('/<!--(.|\n)*-->/', '', $value);
        $value = str_replace("\n", ' ', $value);
        $value = preg_replace('/\s+/', ' ', $value);
        $value = trim($value);
        return $value ?: null;
    }

    public function removeChildElement($parent, $child)
    {
        if (!empty($child)) {
            $parent->removeChild($child);
        }
    }
}

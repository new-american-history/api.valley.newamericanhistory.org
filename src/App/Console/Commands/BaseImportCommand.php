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

    public function elementHasTag($element, $tag)
    {
        return !empty($element) && $element->tagName === $tag;
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
        return !empty($html) ? self::getDomDocument($html, 'loadHTML', $errorsToSkip) : null;
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

    public function getElementHtml($document, $element, $tagsToRemove = [])
    {
        if (empty($element)) {
            return null;
        }

        $html = $document->saveHTML($element);
        foreach ($tagsToRemove as $tag) {
            $html = self::removeTags($html, $tag);
        }
        $html = self::getNormalizedValue($html);
        return $html;
    }

    public function getElementsWithAttribute($parentElement, $tag, $attribute, $type)
    {
        $possibleElements = $parentElement->getElementsByTagName($tag);
        $elements = [];

        foreach ($possibleElements as $possibleElement) {
            if (self::elementHasAttribute($possibleElement, $attribute, $type)) {
                $elements[] = $possibleElement;
            }
        }
        return $elements;
    }

    public function getElementValue($element, $nullValues = [])
    {
        if (empty($element)) {
            return null;
        }

        $value = $element ? $element->nodeValue : null;
        $value = self::getNormalizedValue($value);

        if (in_array($value, $nullValues)) {
            return null;
        }

        return $value;
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

    public function getFirstElementWithAttribute($parentElement, $tag, $attribute, $type)
    {
        $possibleElements = $parentElement->getElementsByTagName($tag);

        foreach ($possibleElements as $possibleElement) {
            if (self::elementHasAttribute($possibleElement, $attribute, $type)) {
                return $possibleElement;
            }
        }
        return null;
    }

    public function getFirstElementValueByTagName($document, $tagName, $nullValues = [])
    {
        $element = self::getFirstElementByTagName($document, $tagName);
        return self::getElementValue($element, $nullValues);
    }

    public function getFormattedDate($value)
    {
        $value = str_replace('?', '1', $value);
        $value = str_replace('xx', '01', $value);
        $dateTime = strtotime($value);
        if (date('Y', $dateTime) >= date('Y')) {
            return null;
        }
        return !empty($dateTime) ? date('Y-m-d', $dateTime) : null;
    }

    public function getKeywords($document)
    {
        $elements = $document->getElementsByTagName('term') ?? [];
        $keywords = [];

        foreach ($elements as $element) {
            $value = self::getElementValue($element);

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
        $value = preg_replace('/<!--.*-->/', '', $value);
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

    public function removeTags($value, $tag)
    {
        return preg_replace('/<\/?' . $tag . '[^>]*>/', '', $value);
    }
}

<?php

namespace Domain\Shared\Traits;

use DOMDocument;

trait HasManipulatedHtml
{
    protected static $hiTagElementMap = [
        'bold' => 'b',
        'italic' => 'i',
        'super' => 'sup',
        'underline' => 'u',
    ];

    public function getDomDocumentWithHtml($html)
    {
        return self::getDomDocument($html, 'loadHTML');
    }

    public function getDomDocument($data, $function)
    {
        libxml_use_internal_errors(true);

        $document = new DOMDocument();
        $document->$function($data, LIBXML_HTML_NODEFDTD);

        libxml_use_internal_errors(false);

        return $document ?? null;
    }

    public function getElementHtml($document, $element, $tagsToRemove = [])
    {
        $html = $document->saveHTML($element);
        foreach ($tagsToRemove as $tag) {
            $html = self::removeTags($html, $tag);
        }
        $html = self::getNormalizedValue($html);
        return $html ;
    }

    public function getFirstElementByTagName($document, $tagName)
    {
        return $document->getElementsByTagName($tagName)->item(0) ?? null;
    }

    public function getNormalizedValue($value)
    {
        $value = preg_replace('/<!--(.|\n)*-->/', '', $value);
        $value = str_replace("\n", ' ', $value);
        $value = preg_replace('/\s+/', ' ', $value);
        $value = trim($value);
        return $value ?: null;
    }

    public function handleEmphTags($document, $element)
    {
        $emphasizedElements = $element->getElementsByTagName('emph');

        while (!empty($emphasizedElements) && !empty($emphasizedElements->item(0))) {
            $emphasizedElement = $emphasizedElements->item(0);

            $rend = $emphasizedElement->getAttribute('rend');
            $newTag = self::$hiTagElementMap[$rend] ?? 'i';

            $newElement = self::replaceElementTags($document, $emphasizedElement, 'emph', $newTag);
            $emphasizedElement->parentNode->replaceChild($document->importNode($newElement, true), $emphasizedElement);
        }
        return $element;
    }

    public function handleHiTags($document, $element)
    {
        $highlightedElements = $element->getElementsByTagName('hi');

        while (!empty($highlightedElements) && !empty($highlightedElements->item(0))) {
            $highlightedElement = $highlightedElements->item(0);

            $rend = $highlightedElement->getAttribute('rend');
            $newTag = self::$hiTagElementMap[$rend] ?? 'u';

            $newElement = self::replaceElementTags($document, $highlightedElement, 'hi', $newTag);
            $highlightedElement->parentNode->replaceChild($document->importNode($newElement, true), $highlightedElement);
        }
        return $element;
    }

    public function handleUnclearTags($document, $element)
    {
        $unclearElements = $element->getElementsByTagName('unclear');

        while (!empty($unclearElements) && !empty($unclearElements->item(0))) {
            $unclearElement = $unclearElements->item(0);

            $value = $unclearElement->nodeValue;
            $newValue = '<i>[Unclear' . (!empty($value) ? (': ' . $value) : '') . ']</i>';

            $newElement = self::makeElementFromValue($newValue);
            $unclearElement->parentNode->replaceChild($document->importNode($newElement, true), $unclearElement);
        }
        return $element;
    }

    public function makeElementFromValue($value)
    {
        $document = self::getDomDocumentWithHtml($value);
        return self::getFirstElementByTagName($document, 'body');
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

    public function removeTagsAndContents($element, $tag)
    {
        $figures = $element->getElementsByTagName('figure');
        foreach ($figures as $figure) {
            self::removeChildElement($element, $figure);
        }
        return $element;
    }

    public function replaceTags($value, $originalTag, $newTag)
    {
        $value = preg_replace('/<' . $originalTag . '[^>]*>/', '<' . $newTag . '>', $value);
        $value = preg_replace('/<\/' . $originalTag . '[^>]*>/', '</' . $newTag . '>', $value);
        return $value;
    }

    public function replaceElementTags($document, $element, $originalTag, $newTag)
    {
        $value = self::getElementHtml($document, $element);
        $value = preg_replace('/<' . $originalTag . '[^>]*>/', '<' . $newTag . '>', $value);
        $value = preg_replace('/<\/' . $originalTag . '[^>]*>/', '</' . $newTag . '>', $value);
        return self::makeElementFromValue($value);
    }
}

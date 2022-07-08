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

    public function getWithModernSpellings($document, $element)
    {
        $originalElements = $element->getElementsByTagName('orig');

        while (!empty($originalElements) && !empty($originalElements->item(0))) {
            $originalElement = $originalElements->item(0);

            $value = $originalElement->nodeValue;
            $newValue = $originalElement->getAttribute('reg') ?: self::removeTags($value, 'orig');

            $newElement = self::makeElementFromValue($newValue);
            $newElement = self::removeElementTags($newElement->ownerDocument, $newElement, 'p');
            $originalElement->parentNode->replaceChild($document->importNode($newElement, true), $originalElement);
        }
        return $element;
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

    public function removeElementTags($document, $element, $tag)
    {
        $value = self::getElementHtml($document, $element);
        $value = self::removeTags($value, $tag);
        return self::makeElementFromValue($value);
    }

    public function removeTags($value, $tag)
    {
        return preg_replace('/<\/?' . $tag . '[^>]*>/', '', $value);
    }

    public function removeTagsAndContents($element, $tag)
    {
        $figureElements = $element->getElementsByTagName('figure');

        while (!empty($figureElements) && !empty($figureElements->item(0))) {
            $figureElement = $figureElements->item(0);
            self::removeChildElement($figureElement->parentNode, $figureElement);
        }
        return $element;
    }

    public function replaceElementTags($document, $element, $originalTag, $newTag)
    {
        $value = self::getElementHtml($document, $element);
        $value = self::replaceTags($value, $originalTag, $newTag);
        return self::makeElementFromValue($value);
    }

    public function replaceTags($value, $originalTag, $newTag)
    {
        $value = preg_replace('/<' . $originalTag . '[^>]*>/', '<' . $newTag . '>', $value);
        $value = preg_replace('/<\/' . $originalTag . '[^>]*>/', '</' . $newTag . '>', $value);
        return $value;
    }
}

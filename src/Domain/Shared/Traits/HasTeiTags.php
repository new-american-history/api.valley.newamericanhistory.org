<?php

namespace Domain\Shared\Traits;

use DOMDocument;
use Domain\Shared\Traits\HasManipulatedHtml;

trait HasTeiTags
{
    use HasManipulatedHtml;

    public function toArrayWithModernSpelling(): array
    {
        $result = [];
        $modernResult = [];

        $fields = array_keys($this->toArray());
        foreach ($fields as $field) {
            $result += [$field => self::getOriginalFieldValue($field)];
            if (self::isTeiField($field)) {
                $modernResult += [$field => self::getModernFieldValue($field)];
            }
        }

        $result['modern'] = $modernResult;
        return $result;
    }

    protected function getOriginalFieldValue($field)
    {
        $value = $this->$field;
        if (self::isTeiField($field)) {
            $value = self::getCleanTeiValue($value);
        }
        $value = preg_replace('/<orig.*?reg=\"([^\"]*)\".*?>([^<]*)<\/orig>/', '$2', $value);
        return $value ?: null;
    }

    protected function getModernFieldValue($field)
    {
        $value = $this->$field;
        if (self::isTeiField($field)) {
            $value = self::getCleanTeiValue($value);
        }
        $value = preg_replace('/<orig.*?reg=\"([^\"]*)\".*?>([^<]*)<\/orig>/', '$1', $value);
        return $value ?: null;
    }

    protected function getCleanTeiValue($value)
    {
        if (empty($value)) {
            return null;
        }

        $value = self::removeTags($value, 'add');
        $value = self::removeTags($value, 'dateRange');
        $value = self::removeTags($value, 'name');
        $value = self::removeTags($value, 'note');
        $value = self::removeTags($value, 'pb');
        $value = self::removeTags($value, 'seg');

        $value = preg_replace('/<lb[^>]*\/?>/', '<br>', $value);
        $value = preg_replace('/<\/lb[^>]*>/', '', $value);

        $element = self::makeElementFromValue($value);
        $document = $element->ownerDocument;

        $element = self::removeTagsAndContents($element, 'figure');
        $element = self::handleEmphTags($document, $element);
        $element = self::handleHiTags($document, $element);
        $element = self::handleRefTags($document, $element);
        $element = self::handleUnclearTags($document, $element);

        return self::getElementHtml($document, $element, ['body']);
    }

    protected function isTeiField($field)
    {
        return !empty($this->teiFields) && in_array($field, $this->teiFields);
    }
}

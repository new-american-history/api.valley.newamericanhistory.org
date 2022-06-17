<?php

namespace Domain\Shared\Traits;

trait HasModernSpelling
{
    public function toArrayWithModernSpelling(): array
    {
        $result = [];
        $modernResult = [];

        $fields = array_keys($this->toArray());
        foreach ($fields as $field) {
            $result += [$field => self::getOriginalFieldValue($field)];
            if (in_array($field, $this->modernFields)) {
                $modernResult += [$field => self::getModernFieldValue($field)];
            }
        }

        $result['modern'] = $modernResult;
        return $result;
    }

    protected function getOriginalFieldValue($field)
    {
        $value = $this->$field;
        $value = preg_replace('/<orig.*?reg=\"([^\"]*)\".*?>([^<]*)<\/orig>/', '$2', $value);
        return $value;
    }

    protected function getModernFieldValue($field)
    {
        $value = $this->$field;
        $value = preg_replace('/<orig.*?reg=\"([^\"]*)\".*?>([^<]*)<\/orig>/', '$1', $value);
        return $value;
    }
}

<?php

namespace Domain\Shared\Traits;

trait HasModernSpelling
{
    public function toArrayWithModernSpelling(): array
    {
        $fields = array_keys($this->toArray());
        $res = [];
        foreach ($fields as $field) {
            $res += self::getFieldArray($field);
        }
        return $res;
    }

    protected function getFieldArray($field): array
    {
        if (in_array($field, $this->modernFields)) {
            return [
                $field => self::getOriginalFieldValue($field),
                "{$field}_modern" => self::getModernFieldValue($field),
            ];
        } else {
            return [$field => $this->$field];
        }
    }

    protected function getOriginalFieldValue($field)
    {
        $value = $this->$field;
        $value = preg_replace('/<orig.*?reg=\"([^\"]+)\".*?>([^<]*)<\/orig>/', '$2', $value);
        return $value;
    }

    protected function getModernFieldValue($field)
    {
        $value = $this->$field;
        $value = preg_replace('/<orig.*?reg=\"([^\"]+)\".*?>([^<]*)<\/orig>/', '$1', $value);
        return $value;
    }
}

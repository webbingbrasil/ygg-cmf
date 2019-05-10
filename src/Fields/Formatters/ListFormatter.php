<?php

namespace Ygg\Fields\Formatters;

use Ygg\Fields\AbstractField;

/**
 * Class ListFormatter
 * @package Ygg\Fields\Formatters
 */
class ListFormatter extends FieldFormatter
{

    /**
     * @param AbstractField $field
     * @param mixed         $value
     * @return mixed
     */
    public function toFront(AbstractField $field, $value)
    {
        return collect($value)->map(function ($item) use ($field) {
            $itemArray = [
                $field->itemIdAttribute() => $item[$field->itemIdAttribute()]
            ];

            $field->itemFields()->each(function ($itemField) use ($item, &$itemArray) {
                $key = $itemField->key();

                $itemArray[$key] = isset($item[$key])
                    ? $itemField->formatter()->toFront($itemField, $item[$key])
                    : null;
            });

            return $itemArray;

        })->all();
    }

    /**
     * @param AbstractField $field
     * @param string        $attribute
     * @param mixed         $value
     * @return array
     */
    public function fromFront(AbstractField $field, string $attribute, $value): array
    {
        return collect($value)->map(function ($item) use ($field) {
            $itemArray = [
                $field->itemIdAttribute() => $item[$field->itemIdAttribute()]
            ];

            foreach ($item as $key => $value) {
                $itemField = $field->findItemFormFieldByKey($key);

                if ($itemField) {
                    $itemArray[$key] = $itemField->formatter()
                        ->setInstanceId($this->instanceId)
                        ->fromFront($itemField, $key, $value);
                }
            }

            return $itemArray;

        })->all();
    }
}

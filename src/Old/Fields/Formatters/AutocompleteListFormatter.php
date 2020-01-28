<?php

namespace Ygg\Old\Fields\Formatters;

use Ygg\Old\Fields\AbstractField;

/**
 * Class AutocompleteListFormatter
 * @package Ygg\Old\Fields\Formatters
 */
class AutocompleteListFormatter extends FieldFormatter
{

    /**
     * @param AbstractField $field
     * @param mixed         $value
     * @return mixed
     */
    public function toFront(AbstractField $field, $value)
    {
        $autocompleteField = $field->autocompleteField();

        return collect($value)->map(function ($item) use ($field, $autocompleteField) {
            return [
                $autocompleteField->itemIdAttribute() => $item[$autocompleteField->itemIdAttribute()],
                $autocompleteField->key() => $autocompleteField->formatter()->toFront(
                    $autocompleteField, $item
                )
            ];
        })->all();
    }

    /**
     * @param AbstractField $field
     * @param string        $attribute
     * @param mixed         $value
     * @return array
     */
    public function fromFront(AbstractField $field, string $attribute, $value)
    {
        $autocompleteField = $field->autocompleteField();

        return collect($value)->map(function ($item) use ($field, $autocompleteField) {
            $item = $item[$autocompleteField->key()];

            return [
                $autocompleteField->itemIdAttribute() => $autocompleteField->formatter()->fromFront(
                    $autocompleteField, $autocompleteField->key(), $item
                )
            ];
        })->all();
    }
}

<?php

namespace Ygg\Form\Fields\Formatters;

use function is_array;
use Ygg\Form\Fields\AutocompleteField;
use Ygg\Form\Fields\Field;

/**
 * Class AutocompleteFormatter
 * @package Ygg\Form\Fields\Formatters
 */
class AutocompleteFormatter extends FieldFormatter
{

    /**
     * @param Field|AutocompleteField $field
     * @param mixed $value
     * @return array
     */
    public function toFront(Field $field, $value): ?array
    {
        return $value === null || is_array($value)
            ? $value
            : [$field->itemIdAttribute() => $value];
    }

    /**
     * @param Field|AutocompleteField     $field
     * @param string    $attribute
     * @param mixed    $value
     * @return mixed
     */
    public function fromFront(Field $field, string $attribute, $value)
    {
        return $value === null || is_array($value)
            ? $value[$field->itemIdAttribute()]
            : $value;
    }
}

<?php

namespace Ygg\Fields\Formatters;

use function is_array;
use Ygg\Fields\AutocompleteField;
use Ygg\Fields\AbstractField;

/**
 * Class AutocompleteFormatter
 * @package Ygg\Fields\Formatters
 */
class AutocompleteFormatter extends FieldFormatter
{

    /**
     * @param AbstractField|AutocompleteField $field
     * @param mixed                           $value
     * @return array
     */
    public function toFront(AbstractField $field, $value): ?array
    {
        return $value === null || is_array($value)
            ? $value
            : [$field->itemIdAttribute() => $value];
    }

    /**
     * @param AbstractField|AutocompleteField $field
     * @param string                          $attribute
     * @param mixed                           $value
     * @return mixed
     */
    public function fromFront(AbstractField $field, string $attribute, $value)
    {
        return $value === null || is_array($value)
            ? $value[$field->itemIdAttribute()]
            : $value;
    }
}

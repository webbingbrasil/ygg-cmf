<?php

namespace Ygg\Fields\Formatters;

use Ygg\Fields\AbstractField;
use function is_array;

/**
 * Class AutocompleteFormatter
 * @package Ygg\Fields\Formatters
 */
class AutocompleteFormatter extends FieldFormatter
{

    /**
     * @param AbstractField $field
     * @param mixed         $value
     * @return array
     */
    public function toFront(AbstractField $field, $value): array
    {
        return $value === null || is_array($value)
            ? $value
            : [$field->itemIdAttribute() => $value];
    }

    /**
     * @param AbstractField $field
     * @param string        $attribute
     * @param mixed         $value
     * @return mixed
     */
    public function fromFront(AbstractField $field, string $attribute, $value)
    {
        return $value === null || is_array($value)
            ? $value[$field->itemIdAttribute()]
            : $value;
    }
}

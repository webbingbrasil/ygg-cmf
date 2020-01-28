<?php

namespace Ygg\Old\Fields\Formatters;

use function is_array;
use function is_object;
use Ygg\Old\Fields\AbstractField;

/**
 * Class SelectFormatter
 * @package Ygg\Old\Fields\Formatters
 */
class SelectFormatter extends FieldFormatter
{
    /**
     * @param AbstractField $field
     * @param mixed         $value
     * @return array|mixed
     */
    function toFront(AbstractField $field, $value)
    {
        if ($field->isMultiple()) {
            return collect((array)$value)->map(function ($item) use ($field) {
                return is_array($item) || is_object($item)
                    ? ((array)$item)[$field->getIdAttribute()]
                    : $item;
            })->all();

        } elseif (is_array($value)) {
            // Strip other values is not configured to be multiple
            return $value[0];
        }

        return $value;
    }

    /**
     * @param AbstractField $field
     * @param string        $attribute
     * @param mixed         $value
     * @return array|mixed
     */
    function fromFront(AbstractField $field, string $attribute, $value)
    {
        if ($field->isMultiple()) {
            // We must transform items into associative arrays with the "id" key
            return collect((array)$value)->map(function ($item) {
                return ['id' => $item];
            })->all();

        }

        if (is_array($value)) {
            return $value[0];
        }

        return $value;
    }
}

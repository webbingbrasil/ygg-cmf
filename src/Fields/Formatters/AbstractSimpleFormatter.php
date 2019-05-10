<?php

namespace Ygg\Fields\Formatters;

use Ygg\Fields\AbstractField;

/**
 * Class AbstractSimpleFormatter
 * @package Ygg\Fields\Formatters
 */
abstract class AbstractSimpleFormatter extends FieldFormatter
{

    /**
     * @param AbstractField $field
     * @param mixed         $value
     * @return mixed
     */
    public function toFront(AbstractField $field, $value)
    {
        return $value;
    }

    /**
     * @param AbstractField $field
     * @param string        $attribute
     * @param mixed         $value
     * @return mixed
     */
    public function fromFront(AbstractField $field, string $attribute, $value)
    {
        return $value;
    }
}

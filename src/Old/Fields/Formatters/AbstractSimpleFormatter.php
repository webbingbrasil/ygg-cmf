<?php

namespace Ygg\Old\Fields\Formatters;

use Ygg\Old\Fields\AbstractField;

/**
 * Class AbstractSimpleFormatter
 * @package Ygg\Old\Fields\Formatters
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

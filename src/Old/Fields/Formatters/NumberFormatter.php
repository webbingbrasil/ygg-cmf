<?php

namespace Ygg\Old\Fields\Formatters;

use Ygg\Old\Fields\AbstractField;

/**
 * Class NumberFormatter
 * @package Ygg\Old\Fields\Formatters
 */
class NumberFormatter extends FieldFormatter
{

    /**
     * @param AbstractField $field
     * @param string        $attribute
     * @param mixed         $value
     * @return mixed
     */
    public function fromFront(AbstractField $field, string $attribute, $value)
    {
        return $this->toFront($field, $value);
    }

    /**
     * @param AbstractField $field
     * @param mixed         $value
     * @return mixed
     */
    public function toFront(AbstractField $field, $value)
    {
        return (int)$value;
    }
}

<?php

namespace Ygg\Old\Fields\Formatters;

use Ygg\Old\Fields\AbstractField;

class HtmlFormatter extends FieldFormatter
{

    /**
     * @param AbstractField $field
     * @param               $value
     * @return mixed
     */
    public function toFront(AbstractField $field, $value)
    {
        return $value;
    }

    /**
     * @param AbstractField $field
     * @param string        $attribute
     * @param               $value
     * @return null
     */
    public function fromFront(AbstractField $field, string $attribute, $value)
    {
        return null;
    }
}

<?php

namespace Ygg\Old\Fields\Formatters;

use Ygg\Old\Fields\AbstractField;

class WysiwygFormatter extends FieldFormatter
{

    /**
     * @param AbstractField $field
     * @param               $value
     * @return mixed
     */
    public function toFront(AbstractField $field, $value)
    {
        return [
            'text' => $value
        ];
    }

    /**
     * @param AbstractField $field
     * @param string        $attribute
     * @param               $value
     * @return mixed
     */
    public function fromFront(AbstractField $field, string $attribute, $value)
    {
        return $value['text'] ?? '';
    }
}

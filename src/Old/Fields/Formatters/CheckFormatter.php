<?php

namespace Ygg\Old\Fields\Formatters;

use Ygg\Old\Fields\AbstractField;
use function in_array;
use function is_string;

class CheckFormatter extends FieldFormatter
{

    /**
     * @param AbstractField $field
     * @param mixed         $value
     * @return bool
     */
    public function toFront(AbstractField $field, $value): bool
    {
        return (bool)$value;
    }

    /**
     * @param AbstractField $field
     * @param string        $attribute
     * @param mixed         $value
     * @return bool
     */
    public function fromFront(AbstractField $field, string $attribute, $value): bool
    {
        if (is_string($value) && $value !== '') {
            return !in_array($value, [
                'false', '0', 'off'
            ], true);
        }

        return (bool)$value;
    }
}

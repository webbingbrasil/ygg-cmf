<?php

namespace Ygg\Old\Fields;

use InvalidArgumentException;
use Ygg\Old\Fields\Formatters\AutocompleteListFormatter;

/**
 * Class AutocompleteListField
 * @package Ygg\Old\Fields
 */
class AutocompleteListField extends ListField
{
    /**
     * @param string $key
     * @return $this
     */
    public static function make(string $key): self
    {
        return new static($key, static::FIELD_TYPE, new AutocompleteListFormatter);
    }

    /**
     * @param AbstractField $field
     * @return $this
     */
    public function addItemField(AbstractField $field): self
    {
        if (!$field instanceof AutocompleteField) {
            throw new InvalidArgumentException('AutocompleteList item can only contain one field, and it must be a YggFormAutocompleteField');
        }

        return $this->setItemField($field);
    }

    /**
     * @param AutocompleteField $field
     * @return static
     */
    public function setItemField(AutocompleteField $field): self
    {
        $this->itemFields = [$field];

        return $this;
    }

    /**
     * @return AutocompleteField
     */
    public function autocompleteField(): AutocompleteField
    {
        return $this->itemFields()[0];
    }
}

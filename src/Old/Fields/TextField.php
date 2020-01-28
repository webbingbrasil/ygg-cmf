<?php

namespace Ygg\Old\Fields;

use Ygg\Old\Exceptions\Form\FieldValidationException;
use Ygg\Old\Fields\Formatters\TextFormatter;
use Ygg\Old\Fields\Traits\FieldWithDataLocalization;
use Ygg\Old\Fields\Traits\FieldWithMaxLength;
use Ygg\Old\Fields\Traits\FieldWithPlaceholder;

/**
 * Class TextField
 * @package Ygg\Old\Fields
 */
class TextField extends AbstractField
{
    use FieldWithPlaceholder, FieldWithMaxLength, FieldWithDataLocalization;

    protected const FIELD_TYPE = 'text';

    /**
     * @param string $key
     * @return static
     */
    public static function make(string $key): self
    {
        return new static($key, static::FIELD_TYPE, new TextFormatter);
    }

    /**
     * @return string
     */
    protected function inputType(): string
    {
        return 'text';
    }

    /**
     * @return array
     * @throws FieldValidationException
     */
    public function toArray(): array
    {
        return $this->buildArray([
            'inputType' => $this->inputType(),
            'placeholder' => $this->placeholder,
            'maxLength' => $this->maxLength,
            'localized' => $this->localized
        ]);
    }
}

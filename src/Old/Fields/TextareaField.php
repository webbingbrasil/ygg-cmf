<?php

namespace Ygg\Old\Fields;

use Ygg\Old\Exceptions\Form\FieldValidationException;
use Ygg\Old\Fields\Formatters\TextareaFormatter;
use Ygg\Old\Fields\Traits\FieldWithDataLocalization;
use Ygg\Old\Fields\Traits\FieldWithMaxLength;
use Ygg\Old\Fields\Traits\FieldWithPlaceholder;

/**
 * Class TextareaField
 * @package Ygg\Old\Fields
 */
class TextareaField extends AbstractField
{
    use FieldWithPlaceholder, FieldWithMaxLength, FieldWithDataLocalization;

    protected const FIELD_TYPE = 'textarea';

    /**
     * @var int
     */
    protected $rows;

    /**
     * @param string $key
     * @return static
     */
    public static function make(string $key)
    {
        return new static($key, static::FIELD_TYPE, new TextareaFormatter);
    }

    /**
     * @param int $rows
     * @return $this
     */
    public function setRowCount(int $rows): self
    {
        $this->rows = $rows;

        return $this;
    }

    /**
     * @return array
     * @throws FieldValidationException
     */
    public function toArray(): array
    {
        return $this->buildArray([
            'rows' => $this->rows,
            'placeholder' => $this->placeholder,
            'maxLength' => $this->maxLength,
            'localized' => $this->localized
        ]);
    }

    /**
     * @return array
     */
    protected function validationRules(): array
    {
        return [
            'rows' => 'integer|min:1',
        ];
    }
}

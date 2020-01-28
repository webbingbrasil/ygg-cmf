<?php

namespace Ygg\Old\Fields;

use Ygg\Old\Exceptions\Form\FieldValidationException;
use Ygg\Old\Fields\Formatters\CheckFormatter;

/**
 * Class CheckField
 * @package Ygg\Old\Fields
 */
class CheckField extends AbstractField
{
    protected const FIELD_TYPE = 'check';

    /**
     * @var string
     */
    protected $text;

    /**
     * @param string $key
     * @return static
     */
    public static function make(string $key): self
    {
        return new static($key, static::FIELD_TYPE, new CheckFormatter);
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return array
     * @throws FieldValidationException
     */
    public function toArray(): array
    {
        return $this->buildArray([
            'text' => $this->text
        ]);
    }

    /**
     * @return array
     */
    protected function validationRules(): array
    {
        return [
            'text' => 'required',
        ];
    }
}

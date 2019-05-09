<?php

namespace Ygg\Fields;

use Ygg\Exceptions\Form\FieldValidationException;
use Ygg\Fields\Formatters\CheckFormatter;

/**
 * Class CheckField
 * @package Ygg\Fields
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
        return parent::buildArray([
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

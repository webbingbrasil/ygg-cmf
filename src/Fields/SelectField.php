<?php

namespace Ygg\Fields;

use Ygg\Exceptions\Form\FieldValidationException;
use Ygg\Fields\Formatters\SelectFormatter;
use Ygg\Fields\Traits\FieldWithDataLocalization;
use Ygg\Fields\Traits\FieldWithOptions;

/**
 * Class SelectField
 * @package Ygg\Fields
 */
class SelectField extends AbstractField
{
    use FieldWithOptions, FieldWithDataLocalization;

    protected const FIELD_TYPE = 'select';

    /**
     * @var array
     */
    protected $options;

    /**
     * @var bool
     */
    protected $multiple = false;

    /**
     * @var bool
     */
    protected $clearable = false;

    /**
     * @var int
     */
    protected $maxSelected;

    /**
     * @var string
     */
    protected $display = 'list';

    /**
     * @var string
     */
    protected $idAttribute = 'id';

    /**
     * @var bool
     */
    protected $inline = false;

    /**
     * @param string $key
     * @return $this
     */
    public static function make(string $key): self
    {
        return new static($key, static::FIELD_TYPE, new SelectFormatter);
    }

    /**
     * @param mixed $options
     * @return $this
     */
    public function setOptions($options): self
    {
        $this->options = self::formatOptions($options);

        return $this;
    }

    /**
     * @return $this
     */
    public function multipleSelect(): self
    {
        $this->multiple = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function singleSelect(): self
    {
        $this->multiple = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearable(): self
    {
        $this->clearable = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function notClearable(): self
    {
        $this->clearable = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function withInline(): self
    {
        $this->inline = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function withoutInline(): self
    {
        $this->inline = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function displayAsList(): self
    {
        $this->display = 'list';

        return $this;
    }

    /**
     * @return $this
     */
    public function displayAsDropdown(): self
    {
        $this->display = 'dropdown';

        return $this;
    }

    /**
     * @param int $maxSelected
     * @return $this
     */
    public function setMaxSelected(int $maxSelected): self
    {
        $this->maxSelected = $maxSelected;

        return $this;
    }

    /**
     * @return $this
     */
    public function withUnlimitedSelect(): self
    {
        $this->maxSelected = null;

        return $this;
    }

    /**
     * @return bool
     */
    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    /**
     * @return string
     */
    public function getIdAttribute(): string
    {
        return $this->idAttribute;
    }

    /**
     * @param string $idAttribute
     * @return $this
     */
    public function setIdAttribute(string $idAttribute): self
    {
        $this->idAttribute = $idAttribute;

        return $this;
    }

    /**
     * @return array
     * @throws FieldValidationException
     */
    public function toArray(): array
    {
        return parent::buildArray([
            'options' => $this->options,
            'multiple' => $this->multiple,
            'clearable' => $this->clearable,
            'display' => $this->display,
            'inline' => $this->inline,
            'maxSelected' => $this->maxSelected,
            'localized' => $this->localized
        ]);
    }

    /**
     * @return array
     */
    protected function validationRules(): array
    {
        return [
            'options' => 'array',
            'multiple' => 'boolean',
            'inline' => 'boolean',
            'clearable' => 'boolean',
            'display' => 'required|in:list,dropdown',
            'maxSelected' => 'int'
        ];
    }
}

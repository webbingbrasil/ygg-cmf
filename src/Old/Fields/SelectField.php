<?php

namespace Ygg\Old\Fields;

use Ygg\Old\Exceptions\Form\FieldValidationException;
use Ygg\Old\Fields\Formatters\SelectFormatter;
use Ygg\Old\Fields\Traits\FieldWithDataLocalization;
use Ygg\Old\Fields\Traits\FieldWithOptions;

/**
 * Class SelectField
 * @package Ygg\Old\Fields
 */
class SelectField extends AbstractField
{
    use FieldWithOptions, FieldWithDataLocalization;

    protected const FIELD_TYPE = 'select';


    /**
     * @var bool
     */
    protected $multiple = false;

    /**
     * @var bool
     */
    protected $clearable = true;

    /**
     * @var int
     */
    protected $maxSelected;

    /**
     * @var boolean
     */
    protected $highlighting = true;

    /**
     * @var boolean
     */
    protected $searchable = false;

    /**
     * @var string
     */
    protected $display = 'list';

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
    public function withHighlight(): self
    {
        $this->highlighting = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function withoutHighlight(): self
    {
        $this->highlighting = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function withSearchable(): self
    {
        $this->searchable = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function withoutSearchable(): self
    {
        $this->searchable = false;

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
     * @return array
     * @throws FieldValidationException
     */
    public function toArray(): array
    {
        return $this->buildArray([
            'options' => $this->options,
            'multiple' => $this->multiple,
            'clearable' => $this->clearable,
            'display' => $this->display,
            'inline' => $this->inline,
            'maxSelected' => $this->maxSelected,
            'localized' => $this->localized,
            'highlighting' => $this->highlighting,
            'searchable' => $this->searchable
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
            'maxSelected' => 'int',
            'highlighting' => 'boolean',
            'searchable' => 'boolean'
        ];
    }
}

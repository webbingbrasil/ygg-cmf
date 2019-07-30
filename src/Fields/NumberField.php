<?php

namespace Ygg\Fields;

use Ygg\Exceptions\Form\FieldValidationException;
use Ygg\Fields\Formatters\NumberFormatter;
use Ygg\Fields\Traits\FieldWithPlaceholder;

/**
 * Class NumberField
 * @package Ygg\Fields
 */
class NumberField extends AbstractField
{
    use FieldWithPlaceholder;

    protected const FIELD_TYPE = 'number';

    /**
     * @var int
     */
    protected $min;

    /**
     * @var int
     */
    protected $max;

    /**
     * @var int
     */
    protected $step = 1;

    /**
     * @var bool
     */
    protected $showControls = true;

    /**
     * @param string $key
     * @return $this
     */
    public static function make(string $key): self
    {
        return new static($key, static::FIELD_TYPE, new NumberFormatter);
    }

    /**
     * @param int $min
     * @return $this
     */
    public function setMin(int $min): self
    {
        $this->min = $min;

        return $this;
    }

    /**
     * @param int $max
     * @return $this
     */
    public function setMax(int $max): self
    {
        $this->max = $max;

        return $this;
    }

    /**
     * @param int $step
     * @return $this
     */
    public function setStep(int $step): self
    {
        $this->step = $step;

        return $this;
    }

    /**
     * @return $this
     */
    public function withControls(): self
    {
        $this->showControls = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function withoutControls(): self
    {
        $this->showControls = false;

        return $this;
    }

    /**
     * @return array
     * @throws FieldValidationException
     */
    public function toArray(): array
    {
        return $this->buildArray([
            'min' => $this->min,
            'max' => $this->max,
            'step' => $this->step,
            'showControls' => $this->showControls,
            'placeholder' => $this->placeholder,
        ]);
    }

    /**
     * @return array
     */
    protected function validationRules(): array
    {
        return [
            'min' => 'integer',
            'max' => 'integer',
            'step' => 'required|integer',
            'showControls' => 'required|bool',
        ];
    }
}

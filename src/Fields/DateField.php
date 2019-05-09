<?php

namespace Ygg\Fields;

use Ygg\Exceptions\Form\FieldValidationException;
use Ygg\Fields\Formatters\DateFormatter;

/**
 * Class DateField
 * @package Ygg\Fields
 */
class DateField extends AbstractField
{
    protected const FIELD_TYPE = 'date';

    /**
     * @var bool
     */
    protected $hasDate = true;

    /**
     * @var bool
     */
    protected $hasTime = false;

    /**
     * @var bool
     */
    protected $mondayFirst = false;

    /**
     * @var string
     */
    protected $minTime = '00:00';

    /**
     * @var string
     */
    protected $maxTime = '23:59';

    /**
     * @var int
     */
    protected $stepTime = 30;

    /**
     * @var string
     */
    protected $displayFormat;

    /**
     * @var string
     */
    protected $language;

    /**
     * @param string $key
     * @return static
     */
    public static function make(string $key)
    {
        $field = new static($key, static::FIELD_TYPE, new DateFormatter());
        $field->language = app()->getLocale();

        return $field;
    }

    /**
     * @return $this
     */
    public function withDate(): self
    {
        $this->hasDate = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function withoutDate(): self
    {
        $this->hasDate = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function withTime() : self
    {
        $this->hasTime = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function withoutTime() : self
    {
        $this->hasTime = false;

        return $this;
    }

    /**
     * @param bool $sundayFirst
     * @return $this
     */
    public function setSundayFirst(bool $sundayFirst = true): self
    {
        return $this->setMondayFirst(!$sundayFirst);
    }

    /**
     * @param bool $mondayFirst
     * @return $this
     */
    public function setMondayFirst(bool $mondayFirst = true): self
    {
        $this->mondayFirst = $mondayFirst;

        return $this;
    }

    /**
     * @param int $hours
     * @param int $minutes
     * @return $this
     */
    public function setMinTime(int $hours, int $minutes = 0): self
    {
        $this->minTime = $this->formatTime($hours, $minutes);

        return $this;
    }

    /**
     * @param int $hours
     * @param int $minutes
     * @return string
     */
    private function formatTime(int $hours, int $minutes): self
    {
        return str_pad($hours, 2, '0', STR_PAD_LEFT)
            .':'
            .str_pad($minutes, 2, '0', STR_PAD_LEFT);
    }

    /**
     * @param int $hours
     * @param int $minutes
     * @return $this
     */
    public function setMaxTime(int $hours, int $minutes = 0): self
    {
        $this->maxTime = $this->formatTime($hours, $minutes);

        return $this;
    }

    /**
     * @param int $step
     * @return $this
     */
    public function setStepTime(int $step): self
    {
        $this->stepTime = $step;

        return $this;
    }

    /**
     * @param string $displayFormat
     * @return $this
     */
    public function setDisplayFormat(string $displayFormat = null): self
    {
        $this->displayFormat = $displayFormat;

        return $this;
    }

    /**
     * @return array
     * @throws FieldValidationException
     */
    public function toArray(): array
    {
        return parent::buildArray([
            'hasDate' => $this->hasDate,
            'hasTime' => $this->hasTime,
            'minTime' => $this->minTime,
            'maxTime' => $this->maxTime,
            'stepTime' => $this->stepTime,
            'mondayFirst' => $this->mondayFirst,
            'displayFormat' => $this->getDisplayFormat(),
            'language' => $this->language
        ]);
    }

    /**
     * @return string
     */
    protected function getDisplayFormat(): string
    {
        if($this->displayFormat) {
            return $this->displayFormat;
        }

        $format = '';

        if($this->hasDate()) {
            $format = 'DD/MM/YYYY';
        }

        if($this->hasTime) {
            $format .= ' HH:mm';
        }

        return trim($format);
    }

    /**
     * @return bool
     */
    public function hasDate(): bool
    {
        return $this->hasDate;
    }

    /**
     * @return bool
     */
    public function hasTime(): bool
    {
        return $this->hasTime;
    }

    /**
     * @return array
     */
    protected function validationRules(): array
    {
        return [
            'hasDate' => 'required|boolean',
            'hasTime' => 'required|boolean',
            'displayFormat' => 'required',
            'minTime' => 'regex:/[0-9]{2}:[0-9]{2}/',
            'maxTime' => 'regex:/[0-9]{2}:[0-9]{2}/',
            'stepTime' => 'integer|min:1|max:60',
            'mondayFirst' => 'required|boolean',
        ];
    }
}

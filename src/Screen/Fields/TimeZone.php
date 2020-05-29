<?php

namespace Ygg\Screen\Fields;

use DateTimeZone;
use Ygg\Screen\Field;

/**
 * Class TimeZone.
 *
 * @method TimeZone autofocus($value = true)
 * @method TimeZone disabled($value = true)
 * @method TimeZone form($value = true)
 * @method TimeZone name(string $value = null)
 * @method TimeZone required(bool $value = true)
 * @method TimeZone tabindex($value = true)
 * @method TimeZone help(string $value = null)
 * @method TimeZone popover(string $value = null)
 * @method TimeZone title(string $value = null)
 */
class TimeZone extends Field
{
    /**
     * @var string
     */
    protected $view = 'platform::fields.select';

    /**
     * Default attributes value.
     *
     * @var array
     */
    protected $attributes = [
        'class'   => 'form-control',
        'options' => [],
    ];

    /**
     * Attributes available for a particular tag.
     *
     * @var array
     */
    protected $inlineAttributes = [
        'accesskey',
        'autofocus',
        'disabled',
        'form',
        'multiple',
        'name',
        'required',
        'size',
        'tabindex',
    ];

    /**
     * @param string|null $name
     *
     * @return self
     */
    public static function make(string $name = null): self
    {
        return (new static())->name($name)->listIdentifiers();
    }

    /**
     * @return self
     */
    public function multiple(): self
    {
        $this->attributes['multiple'] = 'multiple';

        return $this;
    }

    /**
     * @param int $time
     *
     * @return self
     */
    public function listIdentifiers(int $time = DateTimeZone::ALL): self
    {
        $timeZone = collect(DateTimeZone::listIdentifiers($time))->mapWithKeys(static function ($timezone) {
            return [$timezone => $timezone];
        })->toArray();

        $this->set('options', $timeZone);

        return $this;
    }
}

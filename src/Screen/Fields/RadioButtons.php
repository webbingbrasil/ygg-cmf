<?php

namespace Ygg\Screen\Fields;

use Ygg\Screen\Field;

/**
 * Class RadioButtons.
 *
 * @method RadioButtons accesskey($value = true)
 * @method RadioButtons autofocus($value = true)
 * @method RadioButtons disabled($value = true)
 * @method RadioButtons form($value = true)
 * @method RadioButtons name(string $value = null)
 * @method RadioButtons required(bool $value = true)
 * @method RadioButtons size($value = true)
 * @method RadioButtons tabindex($value = true)
 * @method RadioButtons help(string $value = null)
 * @method RadioButtons popover(string $value = null)
 * @method RadioButtons title(string $value = null)
 * @method RadioButtons options(array $value = [])
 */
class RadioButtons extends Field
{
    /**
     * @var string
     */
    protected $view = 'platform::fields.radiobutton';

    /**
     * Default attributes value.
     *
     * @var array
     */
    protected $attributes = [
        'type' => 'radio',
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
        'type',
    ];

    /**
     * @param string|null $name
     *
     * @return self
     */
    public static function make(string $name = null): self
    {
        return (new static())->name($name)->declarateActive();
    }

    /**
     * @return RadioButtons
     */
    public function declarateActive(): self
    {
        return $this->set('active', function (string $key, string $value = null) {
            return $key === $value;
        });
    }
}

<?php

namespace Ygg\Screen\Fields;

use Ygg\Screen\Field;

/**
 * Class Switcher.
 *
 * @method Switcher accesskey($value = true)
 * @method Switcher autocomplete($value = true)
 * @method Switcher autofocus($value = true)
 * @method Switcher checked($value = true)
 * @method Switcher disabled($value = true)
 * @method Switcher form($value = true)
 * @method Switcher formaction($value = true)
 * @method Switcher formenctype($value = true)
 * @method Switcher formmethod($value = true)
 * @method Switcher formnovalidate($value = true)
 * @method Switcher formtarget($value = true)
 * @method Switcher multiple($value = true)
 * @method Switcher name(string $value = null)
 * @method Switcher placeholder(string $value = null)
 * @method Switcher readonly($value = true)
 * @method Switcher required(bool $value = true)
 * @method Switcher tabindex($value = true)
 * @method Switcher value($value = true)
 * @method Switcher type($value = true)
 * @method Switcher help(string $value = null)
 * @method Switcher sendTrueOrFalse($value = true)
 * @method Switcher title(string $value = null)
 */
class Switcher extends Field
{
    /**
     * @var string
     */
    protected $view = 'platform::fields.switch';

    /**
     * Default attributes value.
     *
     * @var array
     */
    protected $attributes = [
        'type'     => 'checkbox',
        'class'    => 'custom-control-input',
        'value'    => false,
        'novalue'  => 0,
        'yesvalue' => 1,
    ];

    /**
     * Attributes available for a particular tag.
     *
     * @var array
     */
    protected $inlineAttributes = [
        'accept',
        'accesskey',
        'autocomplete',
        'autofocus',
        'checked',
        'disabled',
        'form',
        'formaction',
        'formenctype',
        'formmethod',
        'formnovalidate',
        'formtarget',
        'list',
        'max',
        'maxlength',
        'min',
        'multiple',
        'name',
        'pattern',
        'placeholder',
        'readonly',
        'required',
        'size',
        'src',
        'step',
        'tabindex',
        'value',
        'type',
        'novalue',
        'yesvalue',
    ];

    /**
     * @param string|null $name
     *
     * @return self
     */
    public static function make(string $name = null): self
    {
        return (new static())->name($name);
    }
}

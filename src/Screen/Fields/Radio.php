<?php

namespace Ygg\Screen\Fields;

use Ygg\Screen\Field;

/**
 * Class Radio.
 *
 * @method Radio accesskey($value = true)
 * @method Radio autofocus($value = true)
 * @method Radio checked($value = true)
 * @method Radio disabled($value = true)
 * @method Radio form($value = true)
 * @method Radio formaction($value = true)
 * @method Radio formenctype($value = true)
 * @method Radio formmethod($value = true)
 * @method Radio formnovalidate($value = true)
 * @method Radio formtarget($value = true)
 * @method Radio name(string $value = null)
 * @method Radio placeholder(string $value = null)
 * @method Radio readonly($value = true)
 * @method Radio required(bool $value = true)
 * @method Radio tabindex($value = true)
 * @method Radio value($value = true)
 * @method Radio help(string $value = null)
 * @method Radio title(string $value = null)
 */
class Radio extends Field
{
    /**
     * @var string
     */
    protected $view = 'platform::fields.radio';

    /**
     * Default attributes value.
     *
     * @var array
     */
    protected $attributes = [
        'type'   => 'radio',
        'class'  => 'custom-control-input',
        'value'  => null,
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

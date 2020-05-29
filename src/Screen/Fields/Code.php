<?php

namespace Ygg\Screen\Fields;

use Ygg\Screen\Field;

/**
 * Class Input.
 *
 * @method Code name(string $value = null)
 * @method Code value($value = true)
 * @method Code help(string $value = null)
 * @method Code popover(string $value = null)
 * @method Code language($value = true)
 * @method Code lineNumbers($value = true)
 * @method Code height($value = '300px')
 * @method Code readonly($value = true)
 * @method Code title(string $value = null)
 */
class Code extends Field
{
    /**
     * Supported language.
     *
     * markup, html, xml, svg, mathml
     */
    public const MARKUP = 'markup';

    /**
     * Supported language.
     */
    public const CSS = 'css';

    /**
     * Supported language.
     */
    public const CLIKE = 'clike';

    /**
     * Supported language.
     *
     * javascript, js
     */
    public const JS = 'js';

    /**
     * @var string
     */
    protected $view = 'platform::fields.code';

    /**
     * Default attributes value.
     *
     * @var array
     */
    protected $attributes = [
        'class'        => 'form-control',
        'language'     => 'js',
        'lineNumbers'  => true,
        'defaultTheme' => true,
        'height'       => '300px',
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
        'language',
        'lineNumbers',
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
        'type',
        'value',
        'height',
    ];

    /**
     * @param string|null $name
     *
     * @return self
     */
    public static function make(string $name = null): self
    {
        return (new static())
            ->name($name)
            ->addBeforeRender(function () {
                if ($this->get('language') === 'json') {
                    $value = $this->get('value');
                    $this->set('value', json_encode($value));
                }
            });
    }
}

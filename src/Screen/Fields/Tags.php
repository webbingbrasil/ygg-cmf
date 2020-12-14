<?php

namespace Ygg\Screen\Fields;

use Ygg\Screen\Field;

/**
 * Class Tags.
 *
 * @method self accept($value = true)
 * @method self accesskey($value = true)
 * @method self autocomplete($value = true)
 * @method self autofocus($value = true)
 * @method self checked($value = true)
 * @method self disabled($value = true)
 * @method self form($value = true)
 * @method self formaction($value = true)
 * @method self formenctype($value = true)
 * @method self formmethod($value = true)
 * @method self formnovalidate($value = true)
 * @method self formtarget($value = true)
 * @method self list($value = true)
 * @method self max(int $value)
 * @method self maxlength(int $value)
 * @method self min(int $value)
 * @method self multiple($value = true)
 * @method self name(string $value)
 * @method self pattern($value = true)
 * @method self placeholder(string $value = null)
 * @method self readonly($value = true)
 * @method self required(bool $value = true)
 * @method self size($value = true)
 * @method self src($value = true)
 * @method self step($value = true)
 * @method self tabindex($value = true)
 * @method self type($value = true)
 * @method self options($value = null)
 * @method self help(string $value = null)
 * @method self popover(string $value = null)
 * @method self searchRoute(string $value = null)
 */
class Tags extends Field
{
    /**
     * @var string
     */
    public $view = 'platform::fields.tags';

    /**
     * Default attributes value.
     *
     * @var array
     */
    public $attributes = [
        'class'    => 'form-control',
        'multiple' => 'multiple',
        'searchRoute' => 'systems.tag.search',
        'options' => [],
    ];

    /**
     * Attributes available for a particular tag.
     *
     * @var array
     */
    public $inlineAttributes = [
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
        return (new static())->name($name);
    }

    /**
     * @param string|\Closure $name
     *
     * @return \Ygg\Screen\Field|void
     */
    public function modifyName()
    {
        $name = $this->get('name');
        if (substr($name, -1) !== '.') {
            $this->set('name', $name.'[]');
        }

        parent::modifyName();

        return $this;
    }
}

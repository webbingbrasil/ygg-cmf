<?php

namespace Ygg\Actions;


/**
 * Class Button.
 *
 * @method Button name(string $name = null)
 * @method Button modal(string $modalName = null)
 * @method Button icon(string $icon = null)
 * @method Button class(string $classes = null)
 * @method Button method(string $methodName = null)
 * @method Button parameters(array|object $name)
 * @method Button novalidate(bool $novalidate = true)
 * @method Button confirm(string $confirm = true)
 */
class Button extends Action
{
    /**
     * @var string
     */
    protected $view = 'platform::actions.button';

    /**
     * Default attributes value.
     *
     * @var array
     */
    protected $attributes = [
        'class'       => 'btn btn-link',
        'type'        => 'submit',
        'novalidate'  => false,
        'method'      => null,
        'icon'        => null,
        'action'      => null,
        'confirm'     => null,
        'parameters'  => [],
        'turbolinks'  => true,
    ];

    /**
     * Create instance of the button.
     *
     * @param string $name
     *
     * @return Button $name
     */
    public static function make(string $name = ''): self
    {
        return self::buildInstance($name, function () use ($name) {
            $url = url()->current();
            $query = http_build_query($this->get('parameters'));

            $action = "{$url}/{$this->get('method')}?{$query}";
            $this->set('action', $action);
        });
    }
}

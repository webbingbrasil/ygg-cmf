<?php

namespace Ygg\Screen\Fields;

use Ygg\Screen\Field;

/**
 * Class Label.
 *
 * @method Label name(string $value = null)
 * @method Label popover(string $value = null)
 * @method Label title(string $value = null)
 */
class Label extends Field
{
    /**
     * @var string
     */
    protected $view = 'platform::fields.label';

    /**
     * Default attributes value.
     *
     * @var array
     */
    protected $attributes = [
        'id'    => null,
        'value' => null,
    ];

    /**
     * Attributes available for a particular tag.
     *
     * @var array
     */
    protected $inlineAttributes = [
        'class',
    ];

    /**
     * @param string|null $name
     *
     * @return Label
     */
    public static function make(string $name = null): self
    {
        return (new static())->name($name);
    }
}

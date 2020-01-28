<?php

namespace Ygg\Old\Layout\Form;

use Closure;
use Ygg\Old\Layout\Element;

/**
 * Class FormColumn
 * @package Ygg\Old\Layout\Form
 */
class FormColumn implements Element
{
    use HasFieldRows;

    protected $size;

    /**
     * FormRow constructor.
     * @param $size
     */
    public function __construct($size)
    {
        $this->size = $size;
    }

    /**
     * @param string       $legend
     * @param Closure|null $callback
     * @return $this
     */
    public function addFieldset(string $legend, Closure $callback = null): self
    {
        $fieldset = new Fieldset($legend);
        return $this->addElement($fieldset, $callback);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
                'size' => $this->size
            ] + $this->fieldsToArray();
    }
}

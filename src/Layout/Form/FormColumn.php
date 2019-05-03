<?php

namespace Ygg\Layout\Form;

use Closure;
use Ygg\Layout\Column;

/**
 * Class FormColumn
 * @package Ygg\Layout\Form
 */
class FormColumn extends Column
{
    use HasFieldRows;

    /**
     * @param string        $legend
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
            'size' => $this->size,
            'rows' => $this->elements
        ] + $this->fieldsToArray();
    }
}

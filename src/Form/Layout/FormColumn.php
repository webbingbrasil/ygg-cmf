<?php


namespace Ygg\Form\Layout;

use Closure;
use Ygg\Layout\Column as BaseColumn;

class Column extends BaseColumn
{
    /**
     * @param string        $name
     * @param Closure|null $callback
     * @return $this
     */
    public function addFieldset(string $name, Closure $callback = null): self
    {
        $fieldset = new Fieldset($name);
        return $this->addElement($fieldset, $callback);
    }
}
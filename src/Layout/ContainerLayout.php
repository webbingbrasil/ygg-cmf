<?php

namespace Ygg\Layout;

use Closure;

/**
 * Class ContainerLayout
 * @package Ygg\Layout
 */
class ContainerLayout implements Element, Layout
{
    use WithElements;

    /**
     * @param Closure $callback
     * @return $this
     */
    public function addTab(Closure $callback): self
    {
        $element = new Row();
        return $this->addElement($element, $callback);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'rows' => $this->elements
        ];
    }
}

<?php

namespace Ygg\Old\Layout;

use Closure;

/**
 * Trait WithElements
 * @package Ygg\Old\Layout
 */
trait WithElements
{

    /**
     * @var array
     */
    protected $elements = [];

    /**
     * @param Element $element
     * @param Closure|null $callback
     * @return $this
     */
    private function addElement(Element $element, Closure $callback = null): self
    {
        if($callback) {
            $callback($element);
        }
        $this->elements[] = $element;

        return $this;
    }
}

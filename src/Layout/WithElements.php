<?php

namespace Ygg\Layout;

use Closure;

/**
 * Trait WithElements
 * @package Ygg\Layout
 */
trait WithElements
{

    /**
     * @var array
     */
    private $elements = [];

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

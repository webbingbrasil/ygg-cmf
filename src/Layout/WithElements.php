<?php


namespace Ygg\Layout;


use Closure;

trait WithElements
{

    /**
     * @var array
     */
    protected $elements;

    /**
     * @param Element $element
     * @param Closure $callback
     * @return $this
     */
    protected function addElement(Element $element, Closure $callback): self
    {
        $callback($element);
        $this->elements[] = $element;

        return $this;
    }
}
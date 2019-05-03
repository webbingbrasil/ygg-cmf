<?php


namespace Ygg\Layout;

use Closure;

/**
 * Class TabbedLayout
 * @package Ygg\Layout
 */
class TabbedLayout implements Element
{
    use HasElements;

    /**
     * @param string  $title
     * @param Closure $callback
     * @return $this
     */
    public function addTab(string $title, Closure $callback): self
    {
        $element = new Tab($title);
        return $this->addElement($element, $callback);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'tabs' => $this->elements
        ];
    }
}
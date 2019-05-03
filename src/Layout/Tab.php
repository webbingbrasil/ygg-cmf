<?php

namespace Ygg\Layout;

use Closure;

/**
 * Class Tab
 * @package Ygg\Layout
 */
class Tab implements Element
{
    use WithElements;

    /**
     * @var string
     */
    protected $title;

    /**
     * @param string $title
     */
    public function __construct(string $title)
    {
        $this->title = $title;
    }

    /**
     * @param Closure $callback
     * @return $this
     */
    public function addRow(Closure $callback): self
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

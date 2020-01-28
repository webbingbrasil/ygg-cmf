<?php

namespace Ygg\Old\Layout;

use Closure;

/**
 * Class Row
 * @package Ygg\Old\Layout
 */
class Row implements Element
{
    use WithElements;

    protected $columnClass = Column::class;

    /**
     * @param int          $size
     * @param Closure|null $callback
     * @return $this
     */
    public function addColumn(int $size, Closure $callback = null): self
    {
        $element = new $this->columnClass($size);
        return $this->addElement($element, $callback);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'columns' => $this->elements
        ];
    }

    /**
     * @param string $class
     * @return $this
     */
    public function useColumnClass(string $class): self
    {
        $this->columnClass = $class;

        return $this;
    }
}

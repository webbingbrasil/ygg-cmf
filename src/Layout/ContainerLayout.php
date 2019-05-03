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
     * @var string
     */
    protected $rowColumnClass = Column::class;

    /**
     * @param string $rowColumnClass
     */
    public function setRowColumnClass(string $rowColumnClass): void
    {
        $this->rowColumnClass = $rowColumnClass;
    }

    /**
     * @param Closure $callback
     * @return $this
     */
    public function addRow(Closure $callback): self
    {
        $element = (new Row())->useColumnClass($this->rowColumnClass);
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

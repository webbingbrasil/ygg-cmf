<?php

namespace Ygg\Layout;

use Closure;

/**
 * Class TabbedLayout
 * @package Ygg\Layout
 */
class TabbedLayout implements Element, Layout
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
     * @param string  $title
     * @param Closure $callback
     * @return $this
     */
    public function addTab(string $title, Closure $callback): self
    {
        $element = new Tab($title);
        $element->setRowColumnClass($this->rowColumnClass);
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

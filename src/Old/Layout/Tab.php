<?php

namespace Ygg\Old\Layout;

use Closure;

/**
 * Class Tab
 * @package Ygg\Old\Layout
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
            'title' => $this->title,
            'rows' => $this->elements
        ];
    }
}

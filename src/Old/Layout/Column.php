<?php

namespace Ygg\Old\Layout;

use Closure;

/**
 * Class Column
 * @package Ygg\Old\Layout
 */
class Column implements Element
{
    use WithElements;

    /**
     * @var int
     */
    protected $size;

    /**
     * @var string
     */
    protected $rowColumnClass = self::class;

    /**
     * @param int $size
     */
    public function __construct(int $size)
    {
        $this->size = $size;
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
            'size' => $this->size,
            'rows' => $this->elements
        ];
    }
}

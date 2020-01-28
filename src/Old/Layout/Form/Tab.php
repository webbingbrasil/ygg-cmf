<?php

namespace Ygg\Old\Layout\Form;

use Closure;
use Ygg\Old\Layout\Element;
use Ygg\Old\Layout\WithElements;

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
     * @param int     $size
     * @param Closure $callback
     * @return $this
     */
    public function addColumn(int $size, Closure $callback): self
    {
        $element = new FormColumn($size);
        return $this->addElement($element, $callback);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'columns' => collect($this->elements)->map(function (Element $element) {
                return $element->toArray();
            })->all()
        ];
    }
}

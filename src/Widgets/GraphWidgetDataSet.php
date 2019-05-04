<?php

namespace Ygg\Widgets;

use Illuminate\Support\Collection;

/**
 * Class GraphWidgetDataSet
 * @package Ygg\Widgets
 */
class GraphWidgetDataSet
{

    /**
     * @var array
     */
    protected $values;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $color;

    /**
     * GraphWidgetDataSet constructor.
     * @param $values
     */
    protected function __construct($values)
    {
        $this->values = $values instanceof Collection
            ? $values->toArray()
            : $values;
    }

    /**
     * @param $values
     * @return $this
     */
    public static function make($values): self
    {
        return new static($values);
    }

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @param string $color
     * @return $this
     */
    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
                'data' => array_values($this->values),
                'labels' => array_keys($this->values)
            ]
            + ($this->label ? ['label' => $this->label] : [])
            + ($this->color ? ['color' => $this->color] : []);
    }
}

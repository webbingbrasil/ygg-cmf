<?php

namespace Ygg\Old\Widgets;

/**
 * Class BarGraphWidget
 * @package Ygg\Old\Widgets
 */
class BarGraphWidget extends GraphWidget
{

    /**
     * @param string $key
     * @return BarGraphWidget
     */
    public static function make(string $key): self
    {
        $widget = new static($key, 'graph');
        $widget->display = 'bar';

        return $widget;
    }
}

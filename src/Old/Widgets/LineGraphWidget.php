<?php

namespace Ygg\Old\Widgets;

/**
 * Class LineGraphWidget
 * @package Ygg\Old\Widgets
 */
class LineGraphWidget extends GraphWidget
{

    /**
     * @param string $key
     * @return LineGraphWidget
     */
    public static function make(string $key): self
    {
        $widget = new static($key, 'graph');
        $widget->display = 'line';

        return $widget;
    }
}

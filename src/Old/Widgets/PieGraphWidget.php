<?php

namespace Ygg\Old\Widgets;

/**
 * Class PieGraphWidget
 * @package Ygg\Old\Widgets
 */
class PieGraphWidget extends GraphWidget
{
    /**
     * @param string $key
     * @return PieGraphWidget
     */
    public static function make(string $key): self
    {
        $widget = new static($key, 'graph');
        $widget->display = 'pie';

        return $widget;
    }
}

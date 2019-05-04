<?php

namespace Ygg\Widgets;

/**
 * Class PieGraphWidget
 * @package Ygg\Widgets
 */
class PieGraphWidget extends GraphWidget
{
    /**
     * @param string $key
     * @return $this
     */
    public static function make(string $key): self
    {
        $widget = new static($key, 'graph');
        $widget->display = 'pie';

        return $widget;
    }
}

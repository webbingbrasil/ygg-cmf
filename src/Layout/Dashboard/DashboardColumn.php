<?php


namespace Ygg\Layout\Dashboard;

use Ygg\Layout\Column;

/**
 * Class DashboardColumn
 * @package Ygg\Layout\Dashboard
 */
class DashboardColumn extends Column
{
    /**
     * @var array
     */
    protected $widgets = [];

    /**
     * @param int    $size
     * @param string $widgetKey
     * @return $this
     */
    public function addWidget(int $size, string $widgetKey): self
    {
        $this->widgets[] = new Widget($size, $widgetKey);
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
                'size' => $this->size,
                'rows' => $this->elements
            ] + $this->widgets;
    }
}

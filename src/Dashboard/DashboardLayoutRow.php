<?php

namespace Ygg\Dashboard\Layout;

/**
 * Class DashboardLayoutRow
 * @package Ygg\Dashboard\Layout
 */
class DashboardLayoutRow
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
        $this->widgets[] = [
            'size' => $size,
            'key' => $widgetKey
        ];

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->widgets;
    }
}

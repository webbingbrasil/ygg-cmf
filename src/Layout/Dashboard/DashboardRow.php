<?php


namespace Ygg\Layout\Dashboard;

use Ygg\Layout\Element;
use Ygg\Layout\WithElements;

/**
 * Class DashboardColumn
 * @package Ygg\Layout\Dashboard
 */
class DashboardRow implements Element
{
    use WithElements;

    /**
     * @param int    $size
     * @param string $widgetKey
     * @return $this
     */
    public function addWidget(int $size, string $widgetKey): self
    {
        $this->elements[] = new Widget($size, $widgetKey);
        return $this;
    }

    /**
     * @return array
     */
    protected function widgetsToArray(): array
    {
        return collect($this->elements)->map(function (Element $element) {
            return $element->toArray();
        })->all();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->widgetsToArray();
    }
}

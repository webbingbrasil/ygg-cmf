<?php

namespace Ygg\Old\Layout\Dashboard;

use Ygg\Old\Layout\Element;

/**
 * Class Widget
 * @package Ygg\Old\Layout\Dashboard
 */
class Widget implements Element
{
    /**
     * @var
     */
    protected $key;

    /**
     * @var
     */
    protected $size;

    /**
     * Widget constructor.
     * @param $size
     * @param $key
     */
    public function __construct($size, $key)
    {
        $this->key = $key;
        $this->size = $size;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'size' => $this->size,
            'key' => $this->key
        ];
    }
}

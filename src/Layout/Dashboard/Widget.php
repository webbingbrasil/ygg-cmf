<?php

namespace Ygg\Layout\Dashboard;

use Ygg\Layout\Element;

/**
 * Class Widget
 * @package Ygg\Layout\Dashboard
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
     * @param $key
     * @param $size
     */
    public function __construct($key, $size)
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

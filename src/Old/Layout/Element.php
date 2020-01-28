<?php

namespace Ygg\Old\Layout;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Interface Element
 * @package Ygg\Old\Layout
 */
interface Element extends Arrayable
{

    /**
     * @return array
     */
    public function toArray(): array;

}

<?php

namespace Ygg\Layout;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Interface Element
 * @package Ygg\Layout
 */
interface Element extends Arrayable
{

    /**
     * @return array
     */
    public function toArray(): array;

}
